<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEmployeeCollection extends JsonResource
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
            "user_id"=>$this->user->id,
            "department_id"=>$this->department_id,
            "manger_id"=>$this->manger_id,
            "user_type"=>$this->user->user_type,

            "first_name"=>$this->user->first_name,
            "last_name"=>$this->user->last_name,
            "email"=>$this->user->email,
            "manger"=>$this->is_manger == 1?"":$this->mangerData->full_name??"",
            "is_manger"=>$this->manger,
            "salary"=>$this->salary,
            "department"=>$this->department->name,
            "avatar"=>$this->avatar,
            "full_name"=>$this->full_name,
            "tasks"=>$this->tasks,
            "tasks_count"=>$this->tasks->count()
        ];
    }
}
