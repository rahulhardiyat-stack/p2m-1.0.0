<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Accreditation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'title', 'faculty_id', 'degree',
        'status', 'issued_date', 'expired_date',
        'sk_number', 'document_path'
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expired_date' => 'date'
    ];

    protected $appends = [
        'is_active',
        'is_expired',
        'remaining_days',
        'status_label'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }


    /* ==========================================
     * ACCESSORS (Virtual Attributes)
     * ========================================== */

    public function getIsActiveAttribute(): bool
    {
        if(!$this->expired_date){
            return false;
        }

        return $this->expired_date->isFuture() || $this->expired_date->isToday();
    }

    public function getIsExpiredAttribute(): bool
    {
        return !$this->is_active;
    }

    // Accessor: Sisa Hari
    public function getRemainingDaysAttribute()
    {
        if (!$this->expired_date) return 0;
        return (int) Carbon::now()->diffInDays(Carbon::parse($this->expired_date), false);
    }

    // Accessor: Status Kadaluarsa
    public function getStatusLabelAttribute() : string
    {
        if($this->is_expired) {
            return 'Kadaluarsa';
        }

        if($this->remaining_days <= 180){
            return $this->remaining_days . ' Hari';
        }

        return $this->remaining_days . ' Hari';
    }


    /* ==========================================
     * QUERY SCOPES (Digunakan oleh Controller)
     * ========================================== */
    public function scopeActive($query)
    {
        return $query->where('expired_date', '>=', Carbon::today());
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_date', '<', Carbon::today());
    }

    public function scopeExpiringSoon($query, $days = 180)
    {
        return $query->where('expired_date', '>=', Carbon::today())
            ->where('expired_date', '<=', Carbon::today()->addDays($days));
    }
}
