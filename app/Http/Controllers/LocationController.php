<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    public function publicIndex(Request $request)
    {
        $hasCoords = $request->filled('lat') && $request->filled('lng');

        $lat = $hasCoords ? (float) $request->query('lat') : 0.0;
        $lng = $hasCoords ? (float) $request->query('lng') : 0.0;
        $radius = $request->float('radius', 5.0);
        $q = (string) $request->query('q', '');

        $radius = max(0.5, min(50, $radius));

        $cacheKey = 'public_locations:' . md5(json_encode([
            'has_coords' => $hasCoords,
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius,
            'q' => $q,
            'page' => (int) $request->query('page', 1),
        ]));

        return Cache::remember($cacheKey, 30, function () use ($hasCoords, $lat, $lng, $radius, $q) {
            $query = Location::query();

            if ($hasCoords) {
                $query->nearby($lat, $lng, $radius);
            }

            if ($q !== '') {
                $query->where(function ($q2) use ($q) {
                    $q2->where('name', 'like', '%'.$q.'%')
                        ->orWhere('address', 'like', '%'.$q.'%');
                });
            }

            $paginator = $query
                ->orderByDesc('updated_at')
                ->paginate(20);

            $data = $paginator->getCollection()->map(function (Location $location) {
                return [
                    'id' => $location->id,
                    'name' => $location->name,
                    'address' => $location->address,
                    'lat' => (float) $location->latitude,
                    'lng' => (float) $location->longitude,
                    'stock' => (int) $location->stock,
                    'last_updated' => optional($location->updated_at)->toISOString(),
                    'distance' => isset($location->distance) ? (float) $location->distance : null,
                ];
            })->values();

            return [
                'data' => $data,
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'from' => $paginator->firstItem(),
                    'last_page' => $paginator->lastPage(),
                    'path' => $paginator->path(),
                    'per_page' => $paginator->perPage(),
                    'to' => $paginator->lastItem(),
                    'total' => $paginator->total(),
                ],
            ];
        });
    }
}
