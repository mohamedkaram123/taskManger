<?php

namespace App\Models;

use App\Traits\Userable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Employee extends  Authenticatable implements JWTSubject
{
    use HasFactory ,Userable;

    protected $appends=['full_name'];

    /**
     * Get the user that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'manger_id');
    }

    /**
     * Get the mangerData that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mangerData()
    {
        return $this->belongsTo(Employee::class, 'manger_id');
    }

    /**
     * Get the department that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class,EmployeeTask::class);
    }

    /**
     * Get all of the task_manger for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks_manger()
    {
        return $this->hasMany(Task::class, 'manger_id');
    }

    public function getFullNameAttribute()
    {
        return $this->user?$this->user->first_name . " " .$this->user->last_name:"";
    }

           /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
