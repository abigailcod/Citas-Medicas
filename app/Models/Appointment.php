<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'status',
    ];

    // Relación con paciente
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relación con doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
