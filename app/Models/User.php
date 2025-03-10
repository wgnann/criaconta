<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Uspdev\SenhaunicaSocialite\Traits\HasSenhaunica;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasSenhaunica;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function vinculo() {
        $vinculos = [
            "Alunopd",
            "Alunogr",
            "Alunopos",
            "Estagiario",
            "Docente",
            "Servidor"
        ];

        $vinculo = "Outro";

        foreach ($vinculos as $v) {
            if ($this->hasPermissionTo($v, 'senhaunica')) {
                $vinculo = $v;
            }
        }

        return $vinculo;
    }

    public function accounts() {
        return $this->hasMany(Account::class);
    }
}
