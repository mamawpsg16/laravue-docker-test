<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;

class ServiceController extends Controller
{
    public function index() {
        return Service::with('department')->get();
    }

    public function store(ServiceRequest $request) {
        return Service::create($request->validated());
    }

    public function show(Service $service) {
        return $service->load('department');
    }

    public function update(ServiceRequest $request, Service $service) {
        $service->update($request->validated());
        return $service;
    }

    public function destroy(Service $service) {
        $service->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
