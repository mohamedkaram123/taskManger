<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "title"=>$this->title,
            "desc"=>$this->desc,
            "status"=>$this->status,
            "employee"=>$this->employee->full_name,
            "manger"=>$this->manger->full_name,
            "employee_id"=>$this->employee_id,
            "manger_id"=>$this->manger_id,
        ];
    }
}
