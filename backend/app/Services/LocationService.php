<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationService
{
    public function getAllWithFilters(Request $request)
    {
        $query = Location::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        return $query->get();
    }

    public function create(array $data)
    {
        return Location::create($data);
    }
}
