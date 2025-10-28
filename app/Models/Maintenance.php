<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'type',
        'status',
        'description',
        'problem_reported',
        'work_performed',
        'parts_used',
        'cost',
        'scheduled_date',
        'start_date',
        'end_date',
        'technician_name',
        'duration_minutes'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'cost' => 'decimal:2',
        'parts_used' => 'array',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function calculateDuration()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInMinutes($this->end_date);
        }
        return null;
    }

    public function markAsCompleted($workPerformed, $partsUsed = [], $cost = null)
    {
        $this->update([
            'status' => 'completed',
            'end_date' => now(),
            'work_performed' => $workPerformed,
            'parts_used' => $partsUsed,
            'cost' => $cost,
            'duration_minutes' => $this->calculateDuration()
        ]);

        // Mettre à jour le statut de l'équipement
        $this->equipment->update(['status' => 'operational']);
    }
}