<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsExport extends Component
{
    public function download(): StreamedResponse
    {
        $fileName = 'stock-report-'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'location_id',
                'location_name',
                'distributor_id',
                'distributor_name',
                'address',
                'latitude',
                'longitude',
                'stock',
                'capacity',
                'is_open',
                'updated_at',
            ]);

            Location::query()
                ->with('distributor')
                ->orderBy('distributor_id')
                ->orderBy('name')
                ->chunk(500, function ($locations) use ($out) {
                    foreach ($locations as $location) {
                        fputcsv($out, [
                            $location->id,
                            $location->name,
                            $location->distributor_id,
                            $location->distributor?->name,
                            $location->address,
                            $location->latitude,
                            $location->longitude,
                            $location->stock,
                            $location->capacity,
                            $location->is_open ? 1 : 0,
                            optional($location->updated_at)->toDateTimeString(),
                        ]);
                    }
                });

            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.reports-export')
            ->layout('components.layouts.app', ['title' => __('Export Reports')]);
    }
}
