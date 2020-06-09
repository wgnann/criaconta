import argparse
import criaconta
import os
import pwgen
import smtplib
import ssl
import subprocess
import unidecode
from decouple import config
from email.message import EmailMessage

def show(account):
    acc_id = account['id']
    username = account['username']
    group = account['group']
    if (group == "spec"):
        group = "\x1b[91m"+group+"\x1b[0m"
    name = account['name']
    return "  id: %s, username: %s, group: %s, name: %s"%(acc_id, username, group, name)

def ssh(host, command):
    run = ["/usr/bin/ssh", "-l", "root", host]+command.split()
    return subprocess.call(run)

def check(account):
    username = account['username']
    return subprocess.call(["/usr/sbin/smbldap-usershow", username])

def add(account):
    username = account['username']
    name = unidecode.unidecode(account['name'])
    group = account['group']
    skel = config('SKEL_DIR')
    home = "/home/%s/%s"%(group, username)
    return subprocess.call(["/usr/sbin/smbldap-useradd", "-a", "-c", name, "-d", home, "-g", group, "-k", skel, "-m", username])

def setquota(account):
    username = account['username']
    soft = config('DISK_QUOTA', cast=int)
    hard = round(soft*1.2)
    return ssh("nfs.ime.usp.br", "setquota -a -u %s %s %s 0 0"%(username, soft, hard))

def password(account, mode):
    username = account['username']
    passwd = account['passwd']

    if (mode == 'add'):
        principal = config('KRB_ADD_PRINCIPAL')
        keytab = config('KRB_ADD_KEYTAB')
        command = "addprinc -pw %s %s"%(passwd, username)
    elif (mode == 'cpw'):
        principal = config('KRB_CPW_PRINCIPAL')
        keytab = config('KRB_CPW_KEYTAB')
        command = "cpw -pw %s %s"%(passwd, username)
    return subprocess.call(["/usr/bin/kadmin", "-p", principal, "-k", "-t", keytab, "-q", command])

def subscribe(account):
    email = account['username']+'@ime.usp.br'
    group = 'g-'+account['group']
    return ssh("lists.ime.usp.br", "echo %s | add_members -r - -a n -w n %s"%(email, group))

def pykota(account):
    username = account['username']
    group = account['group']
    quota = config('PRINT_QUOTA', cast=int)
    if group in ['mac', 'mae', 'map', 'mat']:
        quota = config('PROF_PRINT_QUOTA', cast=int)
    grace = quota+50
    ssh("cups.ime.usp.br", "pkusers -a %s"%(username))
    ssh("cups.ime.usp.br", "edpykota -P'*' -m 500 --add %s"%(username))
    return ssh("cups.ime.usp.br", "edpykota -PIME -S %s -H %s -m 500 --add %s"%(quota, grace, username))

def mail(account, subject, template):
    receiver = account['owner_email']
    server = config('SMTP_SERVER')
    sender = config('MAIL_SENDER')
    content = open(template, 'r').read()

    message = EmailMessage()
    message['Subject'] = subject
    message['From'] = sender
    message['To'] = receiver
    message.set_content(content.format(**account))

    context = ssl.create_default_context()
    with smtplib.SMTP(server, 25) as smtp:
        smtp.starttls(context=context)
        smtp.send_message(message)

def group_acl(account):
    group = account['group']
    username = account['username']
    members = input("insira os membros separados por vírgula. (e.g. rick,astley,roll): ")
    home = "/home/%s/%s"%(group, username)
    k5login = ""
    for member in members.split(','):
        k5login += member+"@IME.USP.BR\n"

    with open(home+"/.k5login", 'w') as file:
        file.write(k5login)

def create_backend(account):
    group = account['group']
    username = account['username']
    account['passwd'] = pwgen.pwgen(12)
    home = "/home/%s/%s"%(group, username)
    mail_body = "create.txt"

    if(check(account) == 0):
        return 1

    add(account)
    os.chmod(home, 0o711)
    setquota(account)
    if (account['type'] == 'institucional'):
        print("Observações: %s"%(account['obs']))
        password(account, 'add')
    else:
        password(account, 'add')
        pykota(account)
    mail(account, 'Pedido de criação de conta', mail_body)

    return 0

def create(account):
    api = criaconta.CriaConta()
    acc_id = str(account['id'])
    username = account['username']
    if (create_backend(account) != 0):
        print("conta "+username+" fracassou no backend.")
    else:
        status = api.activate(acc_id)
        if (status == 200):
            print("conta "+username+" criada.")
        elif (status == 404):
            print("conta "+username+" fracassou na API.")
        else:
            print("comando inválido.\n")

def pykota_del(account):
    username = account['username']
    return ssh("cups.ime.usp.br", "pkusers -d %s"%(username))

def username_group(username):
    group = subprocess.run(["id", "-gn", username], stdout=subprocess.PIPE)
    if (group.returncode == 0):
        return group.stdout.decode().strip()
    else:
        return None

def backup_home(account):
    username = account['username']
    backup_dir = config('BACKUP_DIR')
    home = "/home/%s/%s"%(account['group'], username)
    ssh("nfs.ime.usp.br", "tar czf %s/%s.tar.gz %s"%(backup_dir, username, home))
    return ssh("nfs.ime.usp.br", "ls -lash %s/%s.tar.gz"%(backup_dir, username))

def user_del(account):
    username = account['username']
    principal = config('KRB_DEL_PRINCIPAL')
    keytab = config('KRB_DEL_KEYTAB')
    command = "delprinc %s"%(username)
    subprocess.call(["/usr/sbin/smbldap-userdel", "-r", username])
    return subprocess.call(["/usr/bin/kadmin", "-p", principal, "-k", "-t", keytab, "-q", command])

def delete(account):
    if (check(account) == 0):
        print("vou apagar, hein?")
        remover = input("diga sim: ")
        if (remover == "sim"):
            pykota_del(account)
            backup_home(account)
            user_del(account)
            mail(account, 'Pedido de remoção de conta', 'delete.txt')
        else:
            print("remoção cancelada.\n")
    else:
        print("username: "+account['username']+" não encontrado no backend.\n")

def interactive_mode():
    api = criaconta.CriaConta()

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
                if(account['group'] != "spec"):
                    create(account)
            break
        elif (option == 'c'):
            acc_id = input("qual o id da conta que será criada? ")
            for account in todo:
                if (acc_id == str(account['id'])):
                    create(account)
            input("pressione enter para retornar... ")
        elif (option == 'a'):
            acc_id = input("qual o id da conta para ativar? ")
            status = api.activate(acc_id)
            if (status == 200):
                print("id: "+acc_id+" ativado.")
            elif (status == 404):
                print("id: "+acc_id+" não encontrado.\n")
            else:
                print("comando inválido.\n")
            input("pressione enter para retornar... ")
        elif (option == 'n'):
            acc_id = input("qual o id da conta para não criar? ")
            status = api.cancel(acc_id)
            if (status == 200):
                print("id: "+acc_id+" cancelado.\n")
            elif (status == 404):
                print("id: "+acc_id+" não encontrado.\n")
            else:
                print("comando inválido.\n")
            input("pressione enter para retornar... ")
        elif (option == 'd'):
            username = input("qual o login da conta que será apagada? ")
            status, account = api.user_info(username)
            if (status == 200):
                delete(account)
                acc_id = str(account['id'])
                status = api.delete(acc_id)
                if (status == 200):
                    print("conta "+username+" apagada.")
                elif (status == 404):
                    print("conta "+username+" fracassou na API.")
                else:
                    print("comando inválido.\n")
            elif (status == 404):
                print("username: "+username+" não encontrado na API.\n")
                remover = input("deseja tentar remover uma conta de fora da API? ")
                if (remover.lower() == 's'):
                    account = {
                        'username': username,
                        'group': username_group(username),
                        'owner_email': username+'@ime.usp.br'
                    }
                    delete(account)
            input("pressione enter para retornar... ")
        else:
            break

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--passwd', action='store_true', help="processa todos os pedidos de recuperação de senha")
    parser.add_argument('--create', action='store_true', help="processa todos os pedidos de criação de conta")
    args = parser.parse_args()

    if (args.passwd):
        api = criaconta.CriaConta()
        todo = api.password_requests()
        for request in todo:
            request_id = str(request['id'])
            username = request['username']
            request['passwd'] = pwgen.pwgen()
            password(request, 'cpw')
            mail(request, 'Recuperação de senha', 'passwd.txt')
            status = api.password_reset(request_id)
            if (status == 200):
                print("conta "+username+" criada.")
            elif (status == 404):
                print("conta "+username+" fracassou na API.")
            else:
                print("comando inválido.\n")
    elif (args.create):
        api = criaconta.CriaConta()
        todo = api.list()
        for account in todo:
            if(account['group'] != "spec"):
                print(show(account))
                create(account)
    else:
        interactive_mode()

if __name__ == "__main__":
    main()
