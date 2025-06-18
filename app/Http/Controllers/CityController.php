<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CityController extends Controller {
    public function index() {
        $branches = Branches::with(['dealer', 'country', 'state', 'city', 'deliverableImages', 'ratings'])->get();
        return response()->json($branches);
    }

    public function show($id) {
        $branch = Branches::with(['dealer', 'deliverableImages', 'ratings'])->findOrFail($id);
        return response()->json($branch);
    }

    public function store(StoreBranchRequest $request) {
        $branch = Branches::create($request->validated());
        // Handle banners & thumbnail upload here
        return response()->json(['message' => 'Branch created', 'data' => $branch]);
    }

    public function update(UpdateBranchRequest $request, $id) {
        $branch = Branches::findOrFail($id);
        $branch->update($request->validated());
        return response()->json(['message' => 'Branch updated', 'data' => $branch]);
    }

    public function destroy($id) {
        $branch = Branches::findOrFail($id);
        $branch->delete();
        return response()->json(['message' => 'Branch deleted']);
    }
}
