<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordRequest extends Model
{
    public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
