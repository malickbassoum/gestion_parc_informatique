<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serial_number',
        'category',
        'brand',
        'model',
        'status',
        'specifications',
        'purchase_date',
        'purchase_price',
        'location',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'specifications' => 'array',
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function getMaintenanceStatusAttribute()
    {
        $lastMaintenance = $this->maintenances()
            ->where('status', 'completed')
            ->latest()
            ->first();

        if (!$lastMaintenance) {
            return 'Aucune maintenance effectuée';
        }

        $daysSinceLastMaintenance = now()->diffInDays($lastMaintenance->end_date);

        if ($daysSinceLastMaintenance < 30) {
            return 'Bon état';
        } elseif ($daysSinceLastMaintenance < 90) {
            return 'Maintenance recommandée';
        } else {
            return 'Maintenance requise';
        }
    }

    public function getAgeAttribute()
    {
        if (!$this->purchase_date) {
            return 'Non spécifié';
        }

        $years = $this->purchase_date->diffInYears(now());
        
        if ($years == 0) {
            $months = $this->purchase_date->diffInMonths(now());
            return $months . ' mois';
        }
        
        return $years . ' an(s)';
    }
}