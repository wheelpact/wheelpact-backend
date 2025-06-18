<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Resources\BranchDeliverableImage;
use App\Resources\BranchRating;


class BranchResource extends JsonResource {
    public function toArray($request) {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'dealer_id' => $this->dealer_id,
            'branch_type' => $this->branch_type,
            'services' => $this->branch_services,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'location' => [
                'country' => optional($this->country)->name,
                'state' => optional($this->state)->name,
                'city' => optional($this->city)->name,
            ],
            'map' => [
                'latitude' => $this->map_latitude,
                'longitude' => $this->map_longitude,
            ],
            'banners' => [
                $this->branch_banner1,
                $this->branch_banner2,
                $this->branch_banner3,
            ],
            'thumbnail' => $this->branch_thumbnail,
            'logo' => $this->branch_logo,
            //'deliverables' => BranchDeliverableImage::collection($this->deliverables),
            'ratings' => BranchRating::collection($this->ratings),
            'deliverables' => $this->deliverables->pluck('img_name')->toArray(),

        ];
    }
}
