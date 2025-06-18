<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchDeliverableImage extends JsonResource {
    public function toArray($request) {
        return [
            'id'         => $this->id,
            'branch_id'  => $this->branch_id,
            'img_name'   => $this->img_name,
            'type'       => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
