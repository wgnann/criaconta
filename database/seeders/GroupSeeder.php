<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            ['Funcionário', 'func', 'SERVIDOR'],
            ['Docente Computação', 'mac', 'SERVIDOR'],
            ['Docente Estatística', 'mae', 'SERVIDOR'],
            ['Docente Matemática Aplicada', 'map', 'SERVIDOR'],
            ['Docente Matemática', 'mat', 'SERVIDOR'],
            ['Pós Computação', 'posmac', 'ALUNOPOS'],
            ['Pós Estatística', 'posmae', 'ALUNOPOS'],
            ['Pós Matemática Aplicada', 'posmap', 'ALUNOPOS'],
            ['Pós Matemática', 'posmat', 'ALUNOPOS'],
            ['Pós Bioinformática', 'posbio', 'ALUNOPOS'],
            ['Mestrado Profissional Ensino de Matemática', 'posmpem', 'ALUNOPOS'],
            ['Pós Doutorado Computação', 'posdocmac', 'ALUNOPD'],
            ['Pós Doutorado Estatística', 'posdocmae', 'ALUNOPD'],
            ['Pós Doutorado Matemática Aplicada', 'posdocmap', 'ALUNOPD'],
            ['Pós Doutorado Matemática', 'posdocmat', 'ALUNOPD'],
            ['Graduação Computação', 'gradmac', 'ALUNOGR'],
            ['Graduação Estatística', 'gradmae', 'ALUNOGR'],
            ['Graduação Matemática Aplicada', 'gradmap', 'ALUNOGR'],
            ['Graduação Matemática', 'gradmat', 'ALUNOGR'],
            ['Visitante Computação', 'guestmac', 'OUTRO'],
            ['Visitante Estatística', 'guestmae', 'OUTRO'],
            ['Visitante Matemática Aplicada', 'guestmap', 'OUTRO'],
            ['Visitante Matemática', 'guestmat', 'OUTRO'],
            ['Institucional', 'spec', 'INSTITUCIONAL'],
            ['Estagiário', 'func', 'ESTAGIARIORH'],
            ['Graduação Computação', 'gradmac', 'OUTRO'],
            ['Graduação Estatística', 'gradmae', 'OUTRO'],
            ['Graduação Matemática Aplicada', 'gradmap', 'OUTRO'],
            ['Graduação Matemática', 'gradmat', 'OUTRO'],
            ['Estagiário', 'func', 'OUTRO']
        ];

        foreach ($groups as $group) {
            $g = [
                'name' => $group[0],
                'code' => $group[1],
                'vinculo' => $group[2],
            ];
            Group::create($g);
        }
    }
}
