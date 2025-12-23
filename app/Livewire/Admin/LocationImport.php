<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LocationImport extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $file;

    public array $result = [
        'created' => 0,
        'skipped' => 0,
        'errors' => [],
    ];

    public function import(): void
    {
        $this->authorize('create', Location::class);

        $validated = $this->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xls,xlsx'],
        ]);

        $this->reset('result');

        $spreadsheet = IOFactory::load($validated['file']->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (empty($rows)) {
            $this->addError('file', __('The uploaded file is empty.'));
            return;
        }

        $headerRow = array_shift($rows);
        $headers = $this->normalizeHeaders($headerRow);

        foreach ($rows as $index => $row) {
            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $data = $this->extractRow($row, $headers);

            try {
                DB::transaction(function () use ($data) {
                    $distributor = null;

                    if (! empty($data['distributor_email'])) {
                        $distributor = User::where('email', $data['distributor_email'])->first();
                    }

                    if (! $distributor && $data['distributor_id']) {
                        $distributor = User::where('id', $data['distributor_id'])->first();
                    }

                    if (! $distributor) {
                        throw new \RuntimeException(__('Distributor not found (email/id: :value)', ['value' => $data['distributor_email'] ?: $data['distributor_id']]));
                    }

                    $location = Location::create([
                        'distributor_id' => $distributor->id,
                        'name' => $data['name'],
                        'address' => $data['address'],
                        'latitude' => (float) $data['latitude'],
                        'longitude' => (float) $data['longitude'],
                        'stock' => (int) $data['stock'],
                        'capacity' => $data['capacity'],
                        'is_open' => $data['is_open'],
                        'phone' => $data['phone'],
                        'operating_hours' => $data['operating_hours'],
                    ]);

                    if ($location->stock !== 0) {
                        StockLog::create([
                            'location_id' => $location->id,
                            'change_amount' => $location->stock,
                            'note' => 'Initial stock (bulk import)',
                        ]);
                    }
                });

                $this->result['created']++;
            } catch (\Throwable $e) {
                $this->result['skipped']++;
                $this->result['errors'][] = __('Row :row: :message', [
                    'row' => $index + 2,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        session()->flash('status', __('Import finished. :count locations created.', ['count' => $this->result['created']]));
    }

    public function render()
    {
        return view('livewire.admin.location-import')
            ->layout('components.layouts.app', ['title' => __('Import Locations')]);
    }

    private function normalizeHeaders(array $headerRow): array
    {
        $headers = [];
        foreach ($headerRow as $key => $value) {
            if ($value === null) {
                continue;
            }

            $headers[$key] = Str::of($value)
                ->trim()
                ->lower()
                ->replace([' ', '-'], '_')
                ->toString();
        }

        return $headers;
    }

    private function extractRow(array $row, array $headers): array
    {
        $data = [];
        foreach ($headers as $column => $name) {
            $data[$name] = $row[$column] ?? null;
        }

        $required = ['name', 'address', 'latitude', 'longitude', 'stock'];

        foreach ($required as $field) {
            if (! isset($data[$field]) || $data[$field] === null || trim((string) $data[$field]) === '') {
                throw new \RuntimeException(__('Field :field is required.', ['field' => $field]));
            }
        }

        $distributorEmail = isset($data['distributor_email']) ? trim((string) $data['distributor_email']) : null;
        $distributorId = isset($data['distributor_id']) && $data['distributor_id'] !== '' ? (int) $data['distributor_id'] : null;

        if (! $distributorEmail && ! $distributorId) {
            throw new \RuntimeException(__('Field distributor_email or distributor_id is required.'));
        }

        return [
            'distributor_email' => $distributorEmail,
            'distributor_id' => $distributorId,
            'name' => trim((string) $data['name']),
            'address' => trim((string) $data['address']),
            'latitude' => (float) $data['latitude'],
            'longitude' => (float) $data['longitude'],
            'stock' => (int) $data['stock'],
            'capacity' => isset($data['capacity']) && $data['capacity'] !== '' ? (int) $data['capacity'] : null,
            'is_open' => isset($data['is_open']) ? (bool) in_array(strtolower((string) $data['is_open']), ['1', 'true', 'open'], true) : true,
            'phone' => $data['phone'] ?? null,
            'operating_hours' => $data['operating_hours'] ?? null,
        ];
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
