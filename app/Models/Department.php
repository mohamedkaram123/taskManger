<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * Get all of the empolyee for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function empolyees()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }
}
