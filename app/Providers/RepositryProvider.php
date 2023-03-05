<?php

namespace App\Providers;

use App\Repositories\Contract\DepartmentContract;
use App\Repositories\Contract\EmployeeContract;
use App\Repositories\Contract\TaskContract;
use App\Repositories\Contract\UserContract;
use App\Repositories\DepartmentRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\TaskRepositry;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(UserContract::class,UserRepository::class);
        $this->app->bind(EmployeeContract::class,EmployeeRepository::class);
        $this->app->bind(DepartmentContract::class,DepartmentRepository::class);
        $this->app->bind(TaskContract::class,TaskRepositry::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
