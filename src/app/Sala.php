<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Sala extends Model
{

    use LogsActivity;
    protected $table = "salas";
    protected $fillable = ['sala', 'capacidade'];

    protected static $logAttributes = ['sala', 'capacidade'];
}
