<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Visitante extends Model
{

    use LogsActivity;
    protected $table = "visitantes";
    protected $guarded = ['id', 'created_at', 'updated_at'];


    protected static $logAttributes = ['nome', 'cpf', 'email', 'nascimento'];

}
