import argparse
import requests
from decouple import config

class CriaConta:
    def __init__(self):
        self.api_key = config('API_KEY')
        self.base_url = config('BASE_URL')

    def list(self):
        url = self.base_url+'/accounts/todo?api_key='+self.api_key
        response = requests.get(url)
        return response.json()

    def activate(self, acc_id):
        url = self.base_url+'/accounts/'+acc_id+'/activate?api_key='+self.api_key
        response = requests.get(url)
        return response.status_code

    def cancel(self, acc_id):
        url = self.base_url+'/accounts/'+acc_id+'/cancel?api_key='+self.api_key
        response = requests.get(url)
        return response.status_code

def show_response(status, command, acc_id):
    if (status == 200):
        response = "success"
    elif (status == 404):
        response = "not found"
    else:
        response = "invalid command"
    print (command+': id '+acc_id+': '+response)

def main():
    parser = argparse.ArgumentParser()
    subparsers = parser.add_subparsers(title="command", dest="command", required=True)

    # list
    parser_list = subparsers.add_parser('list')

    # activate
    parser_activate = subparsers.add_parser('activate')
    parser_activate.add_argument("id", type=int, help="id da conta a ser ativada")

    # cancel
    parser_cancel = subparsers.add_parser('cancel')
    parser_cancel.add_argument("id", type=int, help="id da conta a ser cancelada")

    args = parser.parse_args()
    criaconta = CriaConta()

    if (args.command == "list"):
        todo = criaconta.list()
        for account in todo:
            print (account)
    elif (args.command == "activate"):
        status = criaconta.activate(str(args.id))
        show_response(status, args.command, str(args.id))
    elif (args.command == "cancel"):
        status = criaconta.cancel(str(args.id))
        show_response(status, args.command, str(args.id))
    else:
        print("modo inválido.")

if __name__ == "__main__":
    main()
