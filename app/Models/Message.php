<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'message',
    ];

    // Relations for sender

    public function senderDoctor()
    {
        return $this->belongsTo(\App\Models\DoctorProfile::class, 'sender_id');
    }

    public function senderPatient()
    {
        return $this->belongsTo(\App\Models\Patient::class, 'sender_id');
    }

    public function senderPatientMember()
    {
        return $this->belongsTo(\App\Models\PatientMember::class, 'sender_id');
    }

    // Relations for receiver

    public function receiverDoctor()
    {
        return $this->belongsTo(\App\Models\DoctorProfile::class, 'receiver_id');
    }

    public function receiverPatient()
    {
        return $this->belongsTo(\App\Models\Patient::class, 'receiver_id');
    }

    public function receiverPatientMember()
    {
        return $this->belongsTo(\App\Models\PatientMember::class, 'receiver_id');
    }

    // Helper methods to get sender model dynamically
    public function sender()
    {
        switch ($this->sender_type) {
            case 'doctor':
                return $this->senderDoctor;
            case 'patient':
                return $this->senderPatient;
            case 'patient_member':
                return $this->senderPatientMember;
            default:
                return null;
        }
    }

    // Helper method to get receiver model dynamically
    public function receiver()
    {
        switch ($this->receiver_type) {
            case 'doctor':
                return $this->receiverDoctor;
            case 'patient':
                return $this->receiverPatient;
            case 'patient_member':
                return $this->receiverPatientMember;
            default:
                return null;
        }
    }
}
