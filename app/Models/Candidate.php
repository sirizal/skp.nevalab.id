<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'gender',
        'email',
        'phone',
        'address',
        'resume_file',
        'identification_number',
        'identification_file',
        'place_of_birth',
        'date_of_birth',
        'status',
        'position_applied',
        'health_status',
        'marital_status',
        'illness_history',
        'ability_work_shift',
        'notes',
        'education_level',
        'skills',
        'application_date',
        'interview_date',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'application_date' => 'date',
        'interview_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getResumePathAttribute()
    {
        return $this->resume_file ? asset('storage/' . $this->resume_file) : null;
    }

    public function getIdentificationPathAttribute()
    {
        return $this->identification_file ? asset('storage/' . $this->identification_file) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeApplied($query)
    {
        return $query->where('status', 'applied');
    }

    public function scopeInterviewed($query)
    {
        return $query->where('status', 'interviewed');
    }

    public function scopeHired($query)
    {
        return $query->where('status', 'hired');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position_applied', $position);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithSkills($query, $skills)
    {
        return $query->where('skills', 'like', '%' . $skills . '%');
    }

    public function scopeWithHealthStatus($query, $healthStatus)
    {
        return $query->where('health_status', $healthStatus);
    }

    public function scopeWithMaritalStatus($query, $maritalStatus)
    {
        return $query->where('marital_status', $maritalStatus);
    }

    public function scopeWithIllnessHistory($query, $illnessHistory)
    {
        return $query->where('illness_history', 'like', '%' . $illnessHistory . '%');
    }

    public function scopeWithWorkShiftAbility($query, $ability)
    {
        return $query->where('ability_work_shift', 'like', '%' . $ability . '%');
    }

    public function scopeWithEducationLevel($query, $educationLevel)
    {
        return $query->where('education_level', $educationLevel);
    }

    public function scopeWithNotes($query, $notes)
    {
        return $query->where('notes', 'like', '%' . $notes . '%');
    }

    public function scopeCreatedByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
