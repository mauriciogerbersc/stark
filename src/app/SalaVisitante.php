<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SalaVisitante extends Model
{
    use LogsActivity;
    protected $table = "sala_visitante";
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static $logAttributes = ['sala_id', 'visitante_id'];

}
