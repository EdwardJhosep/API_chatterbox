<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_actual',
        'estado',
        'foto_ruta',
        'video_ruta',
        'likes',
        'vistas',
    ];
}
