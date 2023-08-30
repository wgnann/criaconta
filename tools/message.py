# category: (c)riar, (a)tivar, (n)ão criar, (d)eletar, (p)assword
def message(status, category, context, interactive=True):
    msg = {
        "c": [
            "conta {} criada.".format(context),
            "criação de conta fracassou."
        ],
        "a": [
            "id: {} ativado.".format(context),
            "id: {} não encontrado.".format(context)
        ],
        "n": [
            "id: {} cancelado.".format(context),
            "id: {} não encontrado.".format(context)
        ],
        "d": [
            "conta {} apagada.".format(context),
            "remoção fracassou."
        ],
        "p": [
            "senha para {} renovada.".format(context),
            "renovação fracassou."
        ]
    }

    if (category in msg):
        if (status == 200):
            print(msg[category][0])
        elif (status == 404):
            print(msg[category][1])
        else:
            print("status inválido.\n")
    else:
        print("comando inválido.\n")

    if interactive:
        input("pressione enter para retornar... ")
