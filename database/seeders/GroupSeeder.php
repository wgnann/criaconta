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
            ['Funcionário', 'func', 'Servidor'],
            ['Docente Computação', 'mac', 'Docente'],
            ['Docente Estatística', 'mae', 'Docente'],
            ['Docente Matemática Aplicada', 'map', 'Docente'],
            ['Docente Matemática', 'mat', 'Docente'],
            ['Pós Computação', 'posmac', 'Alunopos'],
            ['Pós Estatística', 'posmae', 'Alunopos'],
            ['Pós Matemática Aplicada', 'posmap', 'Alunopos'],
            ['Pós Matemática', 'posmat', 'Alunopos'],
            ['Pós Bioinformática', 'posbio', 'Alunopos'],
            ['Mestrado Profissional Ensino de Matemática', 'posmpem', 'Alunopos'],
            ['Pós Doutorado Computação', 'posdocmac', 'Alunopd'],
            ['Pós Doutorado Estatística', 'posdocmae', 'Alunopd'],
            ['Pós Doutorado Matemática Aplicada', 'posdocmap', 'Alunopd'],
            ['Pós Doutorado Matemática', 'posdocmat', 'Alunopd'],
            ['Graduação Computação', 'gradmac', 'Alunogr'],
            ['Graduação Estatística', 'gradmae', 'Alunogr'],
            ['Graduação Matemática Aplicada', 'gradmap', 'Alunogr'],
            ['Graduação Matemática', 'gradmat', 'Alunogr'],
            ['Visitante Computação', 'guestmac', 'Outro'],
            ['Visitante Estatística', 'guestmae', 'Outro'],
            ['Visitante Matemática Aplicada', 'guestmap', 'Outro'],
            ['Visitante Matemática', 'guestmat', 'Outro'],
            ['Institucional', 'spec', 'Institucional'],
            ['Estagiário', 'func', 'Estagiario'],
            ['Graduação Computação', 'gradmac', 'Outro'],
            ['Graduação Estatística', 'gradmae', 'Outro'],
            ['Graduação Matemática Aplicada', 'gradmap', 'Outro'],
            ['Graduação Matemática', 'gradmat', 'Outro'],
            ['Estagiário', 'func', 'Outro']
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
