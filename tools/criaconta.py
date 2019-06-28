import argparse
import requests
from decouple import config

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
    api_key = config('API_KEY')
    base_url = config('BASE_URL')

    if (args.command == "list"):
        url = base_url+'/accounts/todo?api_key='+api_key
        response = requests.get(url)
        todo = response.json()
        for account in todo:
            print(account)
    elif (args.command == "activate"):
        url = base_url+'/accounts/'+str(args.id)+'/activate?api_key='+api_key
        response = requests.get(url)
        print (response.text)
    elif (args.command == "cancel"):
        url = base_url+'/accounts/'+str(args.id)+'/activate?api_key='+api_key
        response = requests.get(url)
        print (response.text)
    else:
        print("modo inválido.")

if __name__ == "__main__":
    main()
