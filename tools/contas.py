import argparse
import subprocess
import unidecode
from decouple import config

from criaconta import CriaConta
from mail import mail
from message import message
from ssh import ssh_run
from sambatool import create_password, SambaTool

api = CriaConta()
sambatool = SambaTool()

def show(account):
    return "  id: {id}, username: {username}, group: {group}, name: {name}".format(**account)

def create(account, interactive=True):
    acc_id = str(account['id'])
    username = account['username']
    group = account['group']
    # conta institucional tem nome genérico
    if (account['type'] == 'institucional'):
        account['name'] = "grupo {username}".format(**account)
    else:
        account['name'] = unidecode.unidecode(account['name'])
    account['passwd'] = create_password()
    account['home'] = "/home/{group}/{username}".format(**account)
    account['uid'] = sambatool.max_uid()
    mail_body = "create.txt"

    # se existe, libera ativação. senão, cria a conta
    if (sambatool.find_user(username) == None):
        # adiciona
        sambatool.add_user(account)

        # home
        ssh_run("nfs", "mkhomedir_helper %s 0066 /root/skel"%username, port=2222)

        # quota
        soft = config('DISK_QUOTA', cast=int)
        hard = round(soft*1.2)
        ssh_run("nfs", "setquota -a -u %s %s %s 0 0"%(username, soft, hard), port=2222)

        if (account['type'] == 'institucional'):
            print("Observações: {obs}".format(**account))
        mail(account, 'Pedido de criação de conta', mail_body)

    status = api.activate(acc_id)
    message(status, "c", username, interactive)

def backup_home(account):
    backup_dir = config('BACKUP_DIR')
    username = account['username']
    home = "/home/{group}/{username}".format(**account)
    # move o diretório para o backup no servidor de destino
    ssh_run("nfs", "mv %s %s"%(home, backup_dir), port=2222)
    print(ssh_run("nfs", "ls -lashd %s/%s"%(backup_dir, username), port=2222))

def delete(account):
    username = account['username']
    user = sambatool.find_user(username)
    if (user != None):
        # conta não encontrada na API
        if 'group' not in account:
            account['group'] = sambatool.gid2group(user['gidNumber'])
            account['name'] = user['displayName']
            account['owner_email'] = username+'@ime.usp.br'
        print("{name}: grupo {group}".format(**account))
        print("vou apagar, hein?")
        remover = input("diga sim: ")
        if (remover == "sim"):
            try:
                backup_home(account)
            except:
                ignore_home = input("home não encontrada, deseja remover mesmo assim? ")
                if (ignore_home.lower() != 's'):
                    exit(1)
                    print("remoção cancelada.\n")
            sambatool.delete_user(account)
            mail(account, 'Pedido de remoção de conta', 'delete.txt')
        else:
            print("remoção cancelada.\n")
    else:
        print("username: %s não encontrado no backend.\n"%username)

def interactive_mode():
    while (1):
        todo = api.list()
        subprocess.call("clear")
        print("---\nos seguintes usuários serão criados:")
        for account in todo:
            print(show(account))

        print("---")
        option = input("\n  [t]odas as pessoais serão criadas\n  [c]riar uma conta específica\n  [a]tivar uma conta sem criá-la no backend\n  [n]ão criar alguma\n  [d]eletar uma conta\n  default: sair\n\nopção: ")
        if (option == 't'):
            for account in todo:
                create(account)
            break
        elif (option == 'c'):
            acc_id = input("qual o id da conta que será criada? ")
            for account in todo:
                if (acc_id == str(account['id'])):
                    create(account)
                    break
        elif (option == 'a'):
            acc_id = input("qual o id da conta para ativar? ")
            status = api.activate(acc_id)
            message(status, 'a', acc_id)
        elif (option == 'n'):
            acc_id = input("qual o id da conta para não criar? ")
            status = api.cancel(acc_id)
            message(status, 'n', acc_id)
        elif (option == 'd'):
            username = input("qual o login da conta que será apagada? ")
            status, account = api.user_info(username)
            if (status == 200):
                delete(account)
                acc_id = str(account['id'])
                status = api.delete(acc_id)
                message(status, 'd', username)
            elif (status == 404):
                print("username: %s não encontrado na API.\n"%username)
                remover = input("deseja tentar remover uma conta de fora da API? ")
                if (remover.lower() == 's'):
                    delete({'username': username})
                input("pressione enter para retornar... ")
        else:
            break

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--passwd', action='store_true', help="processa todos os pedidos de recuperação de senha")
    parser.add_argument('--create', action='store_true', help="processa todos os pedidos de criação de conta")
    args = parser.parse_args()

    if (args.passwd):
        todo = api.password_requests()
        for request in todo:
            request_id = str(request['id'])
            username = request['username']
            request['passwd'] = sambatool.set_password(username)
            mail(request, 'Recuperação de senha', 'passwd.txt')
            status = api.password_reset(request_id)
            message(status, "p", username)
    elif (args.create):
        todo = api.list()
        for account in todo:
            print(show(account))
            create(account, interactive=False)
    else:
        interactive_mode()

if __name__ == "__main__":
    main()
