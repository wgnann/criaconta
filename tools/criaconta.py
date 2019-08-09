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

    def delete(self, acc_id):
        url = self.base_url+'/accounts/'+acc_id+'/delete?api_key='+self.api_key
        response = requests.get(url)
        return response.status_code

    def password_requests(self):
        url = self.base_url+'/password/requests?api_key='+self.api_key
        response = requests.get(url)
        return response.json()

    def password_reset(self, request_id):
        url = self.base_url+'/password/'+request_id+'/reset?api_key='+self.api_key
        response = requests.get(url)
        return response.status_code

    def user_info(self, username):
        url = self.base_url+'/accounts/'+username+'/info?api_key='+self.api_key
        response = requests.get(url)

        if (response.status_code == 200):
            return (200, response.json())
        else:
            return (404, '{}')

def show_response(status, command, acc_id):
    if (status == 200):
        response = "success"
    elif (status == 404):
        response = "not found"
    else:
        response = "invalid command"
    if (command == "passwd"):
        print(command+': request_id '+acc_id+': '+response)
    else:
        print(command+': id '+acc_id+': '+response)

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

    # delete
    parser_delete = subparsers.add_parser('delete')
    parser_delete.add_argument("id", type=int, help="id da conta a ser apagada")

    # passwd
    parser_passwd = subparsers.add_parser('passwd')
    parser_passwd.add_argument('request_id', type=int, nargs="?", help="id do pedido de renovação de conta")

    # info
    parser_info = subparsers.add_parser('info')
    parser_info.add_argument('username', help="login do usuário")

    args = parser.parse_args()
    criaconta = CriaConta()

    if (args.command == "list"):
        todo = criaconta.list()
        for account in todo:
            print(account)
    elif (args.command == "activate"):
        status = criaconta.activate(str(args.id))
        show_response(status, args.command, str(args.id))
    elif (args.command == "cancel"):
        status = criaconta.cancel(str(args.id))
        show_response(status, args.command, str(args.id))
    elif (args.command == "delete"):
        status = criaconta.delete(str(args.id))
        show_response(status, args.command, str(args.id))
    elif (args.command == "passwd"):
        if (args.request_id != None):
            status = criaconta.password_reset(str(args.request_id))
            show_response(status, args.command, str(args.request_id))
        else:
            reqs = criaconta.password_requests()
            for req in reqs:
                print(req)
    elif (args.command == "info"):
        status, user = criaconta.user_info(args.username)
        if (status == 404):
            print(args.command+': username: '+args.username+': not found')
        else:
            print(user)
    else:
        print("modo inválido.")

if __name__ == "__main__":
    main()
