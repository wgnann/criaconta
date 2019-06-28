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
        todo = response.json()
        for account in todo:
            print(account)

    def activate(self, acc_id):
        url = self.base_url+'/accounts/'+acc_id+'/activate?api_key='+self.api_key
        response = requests.get(url)
        print ('activate: '+acc_id+': '+response.text)

    def cancel(self, acc_id):
        url = self.base_url+'/accounts/'+acc_id+'/cancel?api_key='+self.api_key
        response = requests.get(url)
        print ('cancel: '+acc_id+': '+response.text)

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
        criaconta.list()
    elif (args.command == "activate"):
        criaconta.activate(str(args.id))
    elif (args.command == "cancel"):
        criaconta.cancel(str(args.id))
    else:
        print("modo inválido.")

if __name__ == "__main__":
    main()
