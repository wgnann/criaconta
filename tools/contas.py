import criaconta
import os

def show(account):
    acc_id = account['id']
    username = account['username']
    group = account['group']
    name = account['name']
    return "  id: %s, username: %s, group: %s, name: %s"%(acc_id, username, group, name)

def create(account):
    return "sucesso"

def main():
    api = criaconta.CriaConta()

    while (1):
        todo = api.list()
        os.system("clear")
        print("---\nos seguintes usuários serão criados:")
        for account in todo:
            print(show(account))

        print("---")
        option = input("\n  [c]riar todas as contas\n  [n]ão criar alguma\n  default: sair\n\nopção: ")
        if (option == 'c'):
            for account in todo:
                print(account['username']+': '+create(account))
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
