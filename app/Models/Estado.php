<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'numero_actual',
        'estado',
        'foto_ruta',
        'video_ruta',
        'likes',
        'vistas',
    ];

    // Relación con el modelo Contacto
    public function contacto()
    {
        return $this->belongsTo(Contacto::class, 'numero_actual', 'numeroactual');
    }
}
