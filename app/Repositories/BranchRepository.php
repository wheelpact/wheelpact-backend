<?php

namespace App\Repositories;

use App\Models\Branch;

class BranchRepository {

    public function allByDealerId($dealerId) {
        return Branch::with(['dealer', 'deliverables', 'ratings'])
            ->where('dealer_id', $dealerId)
            ->get();
    }

    public function find($id) {
        return Branch::with(['dealer', 'deliverables', 'ratings'])->findOrFail($id);
    }

    public function create(array $data) {

        return Branch::create($data);
    }


    public function update($id, array $data) {
        $branch = Branch::findOrFail($id);
        $branch->update($data);
        return $branch;
    }

    public function findByIdAndDealer(int $id, int $dealerId): ?Branch {
        return Branch::where('id', $id)
            ->where('dealer_id', $dealerId)
            ->first();
    }

    public function delete($branchId, $dealerId): bool {
        $branch = Branch::where('id', $branchId)
            ->where('dealer_id', $dealerId)
            ->first();

        if (!$branch) {
            return false;
        }

        return $branch->delete();
    }
}
