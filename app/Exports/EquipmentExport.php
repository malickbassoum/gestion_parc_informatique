<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $includeAll;
    protected $templateData;

    public function __construct($includeAll = false, $templateData = null)
    {
        $this->includeAll = $includeAll;
        $this->templateData = $templateData;
    }

    public function collection()
    {
        if ($this->templateData) {
            return collect($this->templateData);
        }

        if ($this->includeAll) {
            return Equipment::withCount('maintenances')->get();
        }

        // Par défaut, exporter seulement les équipements opérationnels
        return Equipment::where('status', 'operational')
                       ->withCount('maintenances')
                       ->get();
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Numéro de série',
            'Catégorie',
            'Marque',
            'Modèle',
            'Statut',
            'Spécifications',
            'Date d\'achat',
            'Prix d\'achat (€)',
            'Localisation',
            'Notes',
            'Nombre de maintenances',
            'Date de création'
        ];
    }

    public function map($equipment): array
    {
        return [
            $equipment->name,
            $equipment->serial_number,
            $equipment->category,
            $equipment->brand,
            $equipment->model,
            $this->getStatusText($equipment->status),
            $equipment->specifications,
            $equipment->purchase_date ? $equipment->purchase_date->format('d/m/Y') : '',
            $equipment->purchase_price ? number_format($equipment->purchase_price, 2) : '',
            $equipment->location,
            $equipment->notes,
            $equipment->maintenances_count ?? 0,
            $equipment->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour l'en-tête
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3498DB']]
            ],
            
            // Style pour les lignes alternées
            'A2:Z1000' => [
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'F8F9FA']
                ]
            ],
        ];
    }

    private function getStatusText($status)
    {
        $statuses = [
            'operational' => 'Opérationnel',
            'maintenance' => 'En maintenance',
            'out_of_service' => 'Hors service'
        ];

        return $statuses[$status] ?? $status;
    }
}