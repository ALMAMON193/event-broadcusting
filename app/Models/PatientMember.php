<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'patient_id',
        'relationship',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship with Patient (User)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Messages sent by this patient member
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id')
            ->where('sender_type', 'patient_member');
    }

    // Messages received by this patient member
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')
            ->where('receiver_type', 'patient_member');
    }

    // All messages (sent or received)
    public function messages()
    {
        return Message::where(function($query) {
            $query->where('sender_id', $this->id)
                ->where('sender_type', 'patient_member');
        })->orWhere(function($query) {
            $query->where('receiver_id', $this->id)
                ->where('receiver_type', 'patient_member');
        });
    }

    // Get the doctor through patient relationship
    public function getDoctor()
    {
        return $this->patient && $this->patient->doctor_id
            ? User::find($this->patient->doctor_id)
            : null;
    }

    // Scope for active patient members
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
