<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_origen',
        'numero_destino',
        'mensaje',
        'foto_nombre',
        'foto_ruta',
        'video_nombre',
        'video_ruta',
    ];
}
