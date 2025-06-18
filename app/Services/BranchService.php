<?php

namespace App\Services;

use App\Repositories\BranchRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use Carbon\Carbon;

class BranchService {
    protected $repo;

    public function __construct(BranchRepository $repo) {
        $this->repo = $repo;
    }

    public function list($user) {
        return $this->repo->allByDealerId($user->id);
    }

    public function show($id) {
        return $this->repo->find($id);
    }

    public function store(array $data) {

        // Convert branch_services to a comma-separated string
        if (is_array($data['branch_services'])) {
            $data['branch_services'] = implode(',', $data['branch_services']);
        }


        return DB::transaction(function () use ($data) {
            // Get current timestamp
            $timestamp = Carbon::now()->format('Ymd_His');

            $existingEmail = Branch::where('email', $data['email'])->exists();
            $existingContact = Branch::where('contact_number', $data['contact_number'])->exists();

            if ($existingEmail || $existingContact) {
                throw new \Exception(
                    ($existingEmail ? 'Email ID already taken. ' : '') .
                        ($existingContact ? 'Contact number already taken.' : '')
                );
            }

            $data['branch_logo'] = request()->file('branch_logo')->store("branches/logos/{$timestamp}", 'public');

            foreach (['branch_banner1', 'branch_banner2', 'branch_banner3'] as $banner) {
                if (request()->hasFile($banner)) {
                    $data[$banner] = request()->file($banner)->store("branches/banners/{$timestamp}", 'public');
                }
            }

            $branch = $this->repo->create($data);

            if (request()->hasFile('deliverables_img_name')) {
                $images = request()->file('deliverables_img_name');
                $types = request()->input('deliverables_type');

                foreach ($images as $index => $image) {
                    $imgPath = $image->store("branches/deliverables/{$timestamp}", 'public');
                    $branch->deliverables()->create([
                        'img_name' => $imgPath,
                        'type' => $types[$index] ?? 'image',
                    ]);
                }
            }

            return $branch->load(['dealer', 'deliverables', 'ratings']);
        });
    }

    public function update($id, array $data) {
        return $this->repo->update($id, $data);
    }

    public function delete($id) {
        return $this->repo->delete($id);
    }
}
