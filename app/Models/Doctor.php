<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'especialidad',
        'numero_colegiatura', // ✅ AGREGAR ESTA LÍNEA
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con citas
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}