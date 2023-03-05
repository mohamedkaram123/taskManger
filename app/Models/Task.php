<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function employees()
    {
        return $this->belongsToMany(Employee::class,EmployeeTask::class);
    }

    /**
     * Get the manger that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manger()
    {
        return $this->belongsTo(Employee::class, 'manger_id');
    }

        /**
     * Get the manger that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'manger_id');
    }
}
