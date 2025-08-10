<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationService
{
    public function getAllWithFilters(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Generar clave de cache única basada en filtros y paginación
        $cacheKey = 'locations_' . md5(json_encode([
            'name'     => $request->input('name'),
            'code'     => $request->input('code'),
            'per_page' => $perPage,
            'page'     => $request->input('page', 1)
        ]));

        // Guardar en cache por 60 segundos
        return Cache::remember($cacheKey, 60, function () use ($request, $perPage) {
            $query = Location::query();

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('code')) {
                $query->where('code', 'like', '%' . $request->code . '%');
            }

            return $query->paginate($perPage);
        });
    }

    public function create(array $data)
    {
        $location = Location::create($data);

        // Limpiar toda la cache de locations
        Cache::flush();

        return $location;
    }
}
