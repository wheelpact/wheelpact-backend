<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchRating extends JsonResource {
    public function toArray($request) {
        return [
            'id'          => $this->id,
            'branch_id'   => $this->branch_id,
            'customer_id' => $this->customer_id,
            'rating'      => $this->rating,
            'message'     => $this->message,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'deleted_at'  => $this->deleted_at,
        ];
    }
}
