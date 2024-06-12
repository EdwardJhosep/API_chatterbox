<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre',
        'numeroactual',
        'numeroagregado',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'numeroagregado', 'mobile_number');
    }
}
