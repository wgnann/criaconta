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

def password(account):
    username = account['username']
    passwd = account['passwd']
    principal = config('KRB_PRINCIPAL')
    keytab = config('KRB_KEYTAB')
    return subprocess.call(["/usr/bin/kadmin", "-p", principal, "-k", "-t", keytab, "-q", "addprinc -pw %s %s"%(passwd, username)])

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

def mail(account, template):
    receiver = account['owner_email']
    server = config('SMTP_SERVER')
    sender = config('MAIL_SENDER')
    content = open(template, 'r').read()

    message = EmailMessage()
    message['Subject'] = 'Pedido de criação de conta'
    message['From'] = sender
    message['To'] = receiver
    message.set_content(content.format(**account))

    context = ssl.create_default_context()
    with smtplib.SMTP(server, 25) as smtp:
        smtp.starttls(context=context)
        smtp.send_message(message)

def create_backend(account):
    account['passwd'] = pwgen.pwgen()
    home = "/home/%s/%s"%(account['group'], account['username'])

    if(check(account) == 0):
        return 1
    add(account)
    os.chmod(home, 0o711)
    setquota(account)
    password(account)
    subscribe(account)
    pykota(account)
    mail(account, 'create.txt')

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

def main():
    api = criaconta.CriaConta()

    while (1):
        todo = api.list()
        subprocess.call("clear")
        print("---\nos seguintes usuários serão criados:")
        for account in todo:
            print(show(account))

        print("---")
        option = input("\n  [t]odas as pessoais serão criadas\n  [c]riar uma conta específica\n  [n]ão criar alguma\n  default: sair\n\nopção: ")
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
        else:
            break

if __name__ == "__main__":
    main()
