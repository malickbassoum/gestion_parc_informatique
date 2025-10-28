<?php

namespace App\Imports;

use App\Models\Equipment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EquipmentImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $errorCount = 0;
    private $errors = [];
    private $previewMode = false;

    public function setPreviewMode($preview)
    {
        $this->previewMode = $preview;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                // Nettoyer les données
                $data = $this->cleanData($row->toArray());
                
                // Valider les données
                $validator = Validator::make($data, $this->getValidationRules(), $this->getValidationMessages());

                if ($validator->fails()) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $index + 2, // +2 car l'en-tête est la ligne 1 et l'index commence à 0
                        'data' => $data,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }

                // Si en mode prévisualisation, ne pas sauvegarder
                if ($this->previewMode) {
                    continue;
                }

                // Formater les données
                $formattedData = $this->formatData($data);

                // Créer ou mettre à jour l'équipement
                Equipment::updateOrCreate(
                    ['serial_number' => $formattedData['serial_number']],
                    $formattedData
                );

                $this->successCount++;

            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = [
                    'row' => $index + 2,
                    'data' => $data ?? [],
                    'errors' => ['Erreur système: ' . $e->getMessage()]
                ];
            }
        }
    }

    private function cleanData($data)
    {
        $cleaned = [];
        
        // Mapping des colonnes possibles
        $mapping = [
            'nom' => 'name',
            'name' => 'name',
            'numero_serie' => 'serial_number',
            'serial_number' => 'serial_number',
            'categorie' => 'category',
            'category' => 'category',
            'marque' => 'brand',
            'brand' => 'brand',
            'modele' => 'model',
            'model' => 'model',
            'statut' => 'status',
            'status' => 'status',
            'specifications' => 'specifications',
            'date_achat' => 'purchase_date',
            'purchase_date' => 'purchase_date',
            'prix_achat' => 'purchase_price',
            'purchase_price' => 'purchase_price',
            'localisation' => 'location',
            'location' => 'location',
            'notes' => 'notes'
        ];

        foreach ($data as $key => $value) {
            $cleanKey = strtolower(trim($key));
            if (isset($mapping[$cleanKey]) && !empty(trim($value))) {
                $cleaned[$mapping[$cleanKey]] = trim($value);
            }
        }

        return $cleaned;
    }

    private function getValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'status' => 'required|in:operational,maintenance,out_of_service',
            'specifications' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ];
    }

    private function getValidationMessages()
    {
        return [
            'name.required' => 'Le nom est obligatoire',
            'serial_number.required' => 'Le numéro de série est obligatoire',
            'category.required' => 'La catégorie est obligatoire',
            'brand.required' => 'La marque est obligatoire',
            'model.required' => 'Le modèle est obligatoire',
            'status.required' => 'Le statut est obligatoire',
            'status.in' => 'Le statut doit être: operational, maintenance ou out_of_service',
            'location.required' => 'La localisation est obligatoire',
        ];
    }

    private function formatData($data)
    {
        // Formater la date d'achat
        if (!empty($data['purchase_date'])) {
            try {
                $data['purchase_date'] = Carbon::createFromFormat('d/m/Y', $data['purchase_date'])
                    ?? Carbon::createFromFormat('Y-m-d', $data['purchase_date'])
                    ?? null;
            } catch (\Exception $e) {
                $data['purchase_date'] = null;
            }
        }

        // Formater le prix
        if (!empty($data['purchase_price'])) {
            $data['purchase_price'] = floatval(str_replace(',', '.', $data['purchase_price']));
        }

        // Normaliser le statut
        $statusMap = [
            'opérationnel' => 'operational',
            'en maintenance' => 'maintenance',
            'hors service' => 'out_of_service',
            'operationnel' => 'operational'
        ];

        if (isset($data['status']) && array_key_exists(strtolower($data['status']), $statusMap)) {
            $data['status'] = $statusMap[strtolower($data['status'])];
        }

        return $data;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}