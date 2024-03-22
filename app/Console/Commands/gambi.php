<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Group;
use App\Models\Account;

/*
    csv é um arquivo dado por várias linhas com:
        fullname:username:groupname

    json é um JSON oriundo do id-admin (ver idmail)
*/

class gambi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gambi {csv} {json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza os usuários existentes no samba, fornecidos via CSV, com os do banco de dados.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        # itera pelos criados no IME
        $f = fopen($this->argument('csv'), 'r');
        while (($data = fgetcsv($f, 0, ':')) !== FALSE) {
            $name = $data[0];
            $user = $data[1];
            $group = $data[2];

            $jf = $this->argument('json');
            $j = json_decode(file_get_contents($jf));

            $email = $user.'@ime.usp.br';

            # itera pelo JSON
            foreach ($j->result as $jmail => $jdata) {
                # devolve o codpes de um email pessoal
                if (in_array($jdata->tipo, ['P', 'O']) and $jmail == $email) {
                    $codpes = $jdata->codpes;

                    # cria usuário com base no codpes
                    $dbuser = User::where('codpes', $codpes)->first();
                    if ($dbuser == null) {
                        $dbuser = new User();
                        $dbuser->name = $name;
                        $dbuser->email = $email;
                        $dbuser->codpes = $codpes;
                        $dbuser->save();
                        echo "usuário $dbuser criado.\n";
                    }

                    # encontra grupos com gambi para func
                    $dbgroup = Group::where('code', $group)->first();
                    if ($group == 'func') {
                        $dbgroup = Group::where('id', 1)->first();
                    }

                    # cria a conta
                    $dbaccount = Account::where('username', $user)->first();
                    if ($dbaccount == null) {
                        $a = new Account();
                        $a->username = $user;
                        $a->name = $name;
                        $a->type = 'pessoal';
                        $a->ativo = 1;
                        $a->user_id = $dbuser->id;
                        $a->group_id = $dbgroup->id;
                        $a->obs = 'criado com script';
                        $a->save();
                        echo "conta $a->username criada.\n";
                    }
                }
            }
        }
        return 0;
    }
}
