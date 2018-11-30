<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['document', 'first_name', 'last_name', 'email', 'phone'];
}
