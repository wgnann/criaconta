import argparse
import criaconta
import grp
import os
import pwd
import pwgen
import smtplib
import ssl
import subprocess
import unidecode
from decouple import config
from email.message import EmailMessage

from message import message

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

def check(account, noout=False):
    username = account['username']
    stdout = None

    if (noout):
        stdout = subprocess.DEVNULL

    return subprocess.call(["/usr/sbin/smbldap-usershow", username], stdout=stdout)

def add(account):
    username = account['username']
    name = unidecode.unidecode(account['name'])
    group = account['group']
    skel = config('SKEL_DIR')
    home = "/home/%s/%s"%(group, username)
    ret = subprocess.call(["/usr/sbin/smbldap-useradd", "-a", "-c", name, "-d", home, "-g", group, username])
    if (ret != 0):
        return ret
    # man rsync "trailing slash"
    skeldir = "%s/"%(skel)
    target = "root@nfs.ime.usp.br:%s"%(home)
    ret = subprocess.call(["/usr/bin/rsync", "-r", skeldir, target])
    if (ret != 0):
        return ret
    ssh("nfs.ime.usp.br", "chmod 711 %s"%(home))
    ssh("nfs.ime.usp.br", "/usr/sbin/service nslcd force-reload")
    return ssh("nfs.ime.usp.br", "chown -R %s: %s"%(username, home))

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
    smtpuser = config('SMTP_USER')
    smtppass = config('SMTP_PASS')
    content = open(template, 'r').read()

    message = EmailMessage()
    message['Subject'] = subject
    message['From'] = sender
    message['To'] = receiver
    message.set_content(content.format(**account))

    context = ssl.create_default_context()
    with smtplib.SMTP(server, 25) as smtp:
        smtp.starttls(context=context)
        smtp.login(smtpuser, smtppass)
        smtp.send_message(message)

def create_password():
    return pwgen.pwgen(12)

def create_backend(account):
    group = account['group']
    username = account['username']
    name = unidecode.unidecode(account['name'])
    account['passwd'] = create_password()
    home = "/home/%s/%s"%(group, username)
    mail_body = "create.txt"

    if (check(account, noout=True) == 0):
        local_account = pwd.getpwnam(username)
        local_name = local_account.pw_gecos
        local_group = grp.getgrgid(local_account.pw_gid).gr_name

        if (name == local_name and group == local_group):
            return 0
        else:
            print("grupo: %s:%s"%(group, local_group))
            print("%s\n%s"%(name, local_name))
            return 1

    if (add(account) != 0):
        return 1

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
        message(status, "c", username)

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
    ssh("nfs.ime.usp.br", "mv %s %s"%(home, backup_dir))
    return ssh("nfs.ime.usp.br", "ls -lashd %s/%s"%(backup_dir, username))

def user_del(account):
    username = account['username']
    principal = config('KRB_DEL_PRINCIPAL')
    keytab = config('KRB_DEL_KEYTAB')
    command = "delprinc %s"%(username)
    subprocess.call(["/usr/sbin/smbldap-userdel", username])
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
            request['passwd'] = create_password()
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
            print(show(account))
            create(account)
    else:
        interactive_mode()

if __name__ == "__main__":
    main()
