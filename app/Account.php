<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function activate()
    {
        $this->ativo = 1;
        return $this->save();
    }
}
