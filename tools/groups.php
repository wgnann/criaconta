<?php
# rodar: php artisan tinker include tools/groups.php

use App\Models\Group;

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
    $g = new Group();
    $g->name = $group[0];
    $g->code = $group[1];
    $g->vinculo = $group[2];
    try {
        if ($g->save()) {
            echo "Adicionado: $g->code\n";
        }
    }
    catch (Exception $e) {
        echo $e->getMessage()."\n";
    }
}
