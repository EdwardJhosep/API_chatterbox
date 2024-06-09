<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'numeroactual',
        'numeroagregado',
    ];

    // No necesitamos definir las marcas de tiempo en este modelo, ya que Eloquent asume que existen
    // created_at y updated_at en la tabla
}
