<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

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
        // Assuming you're using Laravel's Auth system to get the logged-in user
        $data['dealer_id'] = AUTH::id();
        return Branch::create($data);
    }


    public function update($id, array $data) {
        $branch = Branch::findOrFail($id);
        $branch->update($data);
        return $branch;
    }

    public function delete($id) {
        return Branch::destroy($id);
    }
}
