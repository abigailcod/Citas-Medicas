<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ======================
    // RELACIONES (CRUCIAL PARA QUE FUNCIONE EL CONTROLADOR)
    // ======================

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    // ======================
    // MÉTODOS DE ROL
    // ======================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMedico(): bool
    {
        return $this->role === 'medico';
    }

    public function isPaciente(): bool
    {
        return $this->role === 'paciente';
    }
}