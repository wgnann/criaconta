import criaconta
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

def add(account, skel):
    username = account['username']
    name = unidecode.unidecode(account['name'])
    group = account['group']
    home = "/home/%s/%s"%(group, username)
    return subprocess.call(["/usr/sbin/smbldap-useradd", "-a", "-c", name, "-d", home, "-g", group, "-k", skel, "-m", username])

def setquota(account, soft, hard):
    username = account['username']
    return ssh("nfs.ime.usp.br", "setquota -a -u %s %s %s 0 0"%(username, soft, hard))

def create(account):
    skel = config('SKEL_DIR')
    soft_disk = 5120000
    hard_disk = 6144000

    if(check(account) == 0):
        return 1
    add(account, skel)
    setquota(account, soft_disk, hard_disk)

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
