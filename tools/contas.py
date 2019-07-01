import criaconta
import os
import pwgen
import subprocess
import unidecode
from decouple import config

def show(account):
    acc_id = account['id']
    username = account['username']
    group = account['group']
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

def password(account, passwd):
    username = account['username']
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

def create(account):
    home = "/home/%s/%s"%(group, username)
    passwd = pwgen.pwgen()

    if(check(account) == 0):
        return 1
    add(account)
    os.chmod(home, 711)
    setquota(account)
    password(account, passwd)
    subscribe(account)
    pykota(account)

    return 0

def main():
    api = criaconta.CriaConta()

    while (1):
        todo = api.list()
        subprocess.call("clear")
        print("---\nos seguintes usuários serão criados:")
        for account in todo:
            print(show(account))

        print("---")
        option = input("\n  [c]riar todas as contas\n  [n]ão criar alguma\n  default: sair\n\nopção: ")
        if (option == 'c'):
            for account in todo:
                if (create(account) != 0):
                    print("conta "+account['username']+" fracassou.")
                else:
                    print("conta "+account['username']+" criada.")
            break
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
