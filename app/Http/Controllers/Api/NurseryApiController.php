<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NurseryResource;
use App\Models\Nursery;

class NurseryApiController extends Controller
{
    public function index()
    {
        return NurseryResource::collection(Nursery::where('is_active', true)->orderBy('name_ar')->get());
    }

    public function show(Nursery $nursery)
    {
        return new NurseryResource($nursery);
    }
}
