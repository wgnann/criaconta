<?php

use App\Group;

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
    ['Institucional', 'spec', 'INSTITUCIONAL'],
];

foreach ($groups as $group) {
    $g = new Group();
    $g->name = $group[0];
    $g->code = $group[1];
    $g->vinculo = $group[2];
    if ($g->save()) {
        echo "Adicionado: $g->code\n";
    }
}
