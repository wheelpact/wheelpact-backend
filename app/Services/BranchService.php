<?php

namespace App\Services;

use App\Repositories\BranchRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as AUTH;


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

        // Auth system to get the logged-in user
        if (!AUTH::check()) {
            throw new \Exception('User not authenticated');
        }

        $dealer = AUTH::user();
        $data['dealer_id'] = AUTH::id();
        $data['user_code'] = $dealer->user_code ? $dealer->user_code : Carbon::now()->format('Ymd_His');

        return DB::transaction(function () use ($data) {
            // Get current timestamp
            //$timestamp = Carbon::now()->format('Ymd_His');

            $existingEmail = Branch::where('email', $data['email'])->exists();
            $existingContact = Branch::where('contact_number', $data['contact_number'])->exists();

            if ($existingEmail || $existingContact) {
                throw new \Exception(
                    ($existingEmail ? 'Email ID already taken. ' : '') .
                        ($existingContact ? 'Contact number already taken.' : '')
                );
            }

            $data['branch_logo'] = request()->file('branch_logo')->store("branches/logos/{$data['user_code']}", 'public');

            foreach (['branch_banner1', 'branch_banner2', 'branch_banner3'] as $banner) {
                if (request()->hasFile($banner)) {
                    $data[$banner] = request()->file($banner)->store("branches/banners/{$data['user_code']}", 'public');
                }
            }

            if (request()->hasFile('branch_thumbnail')) {
                $data['branch_thumbnail'] = request()->file('branch_thumbnail')
                    ->store("branches/thumbnails/{$data['user_code']}", 'public');
            }

            $branch = $this->repo->create($data);

            // Append deliverable images, in table deliverable_images
            if (request()->hasFile('deliverables_img_name')) {
                $images = request()->file('deliverables_img_name');
                $types = request()->input('deliverables_type');

                foreach ($images as $index => $image) {
                    $imgPath = $image->store("branches/deliverables/{$data['user_code']}", 'public');
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
        return DB::transaction(function () use ($id, $data) {
            $branch = $this->repo->find($id); // with relationships

            $dealer =  AUTH::user();

            $data['dealer_id'] = $dealer->id ?? null;
            $data['user_code'] = $dealer->user_code ? $dealer->user_code : Carbon::now()->format('Ymd_His');

            // Convert services to string if it's an array
            if (isset($data['branch_services']) && is_array($data['branch_services'])) {
                $data['branch_services'] = implode(',', $data['branch_services']);
            }

            // File updates
            if (request()->hasFile('branch_logo')) {
                $data['branch_logo'] = request()->file('branch_logo')->store("branches/logos/{$data['user_code']}", 'public');
            }

            if (request()->hasFile('branch_thumbnail')) {
                $data['branch_thumbnail'] = request()->file('branch_thumbnail')
                    ->store("branches/thumbnails/{$data['user_code']}", 'public');
            }
            
            foreach (['branch_banner1', 'branch_banner2', 'branch_banner3'] as $banner) {
                if (request()->hasFile($banner)) {
                    $data[$banner] = request()->file($banner)->store("branches/banners/{$data['user_code']}", 'public');
                }
            }

            $branch = $this->repo->update($id, $data);

            // Append new deliverable images
            if (request()->hasFile('deliverables_img_name')) {
                $images = request()->file('deliverables_img_name');
                $types = request()->input('deliverables_type');

                foreach ($images as $index => $image) {
                    $imgPath = $image->store("branches/deliverables/{$data['user_code']}", 'public');
                    $branch->deliverables()->create([
                        'img_name' => $imgPath,
                        'type' => $types[$index] ?? 'image',
                    ]);
                }
            }

            return $branch->load(['dealer', 'deliverables', 'ratings']);
        });
    }

    public function getByIdAndDealer(int $id, int $dealerId): ?Branch {
        return $this->repo->findByIdAndDealer($id, $dealerId);
    }

    public function delete(int $branchId, int $dealerId): bool {
        // Call the repository with both IDs to check ownership
        return $this->repo->delete($branchId, $dealerId);
    }
}
