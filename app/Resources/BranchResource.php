<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Resources\BranchDeliverableImage;
use App\Resources\BranchRating;

/**
 * @OA\Schema(
 *     schema="BranchResource",
 *     type="object",
 *     title="Branch Resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Super Auto Service"),
 *     @OA\Property(property="dealer_id", type="integer", example=101),
 *     @OA\Property(property="branch_type", type="integer", example=1),
 *     @OA\Property(property="branch_services", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="country_id", type="integer"),
 *     @OA\Property(property="state_id", type="integer"),
 *     @OA\Property(property="city_id", type="integer"),
 *     @OA\Property(property="address", type="string"),
 *     @OA\Property(property="contact_number", type="string"),
 *     @OA\Property(property="whatsapp_no", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="short_description", type="string"),
 *     @OA\Property(property="map_latitude", type="string"),
 *     @OA\Property(property="map_longitude", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

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
