import paramiko

config_key = "/root/.ssh/id_ed25519"
def ssh_run(host, command, port=22, username='root'):
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(host, port=port, username=username, key_filename=config_key)
    stdin, stdout, stderr = client.exec_command(command)
    if (stdout.channel.recv_exit_status()):
        raise Exception("erro ao executar: %s"%command)
    return stdout.read()
