<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\EmployeeTask;
use App\Models\Filters\QueryFiltersClasses\Where;
use App\Models\Filters\QueryFiltersClasses\WhereLike;
use App\Models\Task;
use App\Repositories\Contract\TaskContract;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class TaskRepositry implements TaskContract
{
    public function create(array $data)
    {
        $task = new Task();
        $task->manger_id = $data["manger_id"];
        $task->employee_id = $data["employee_id"];
        $task->title = $data["title"];
        $task->desc = $data["desc"];
        $task->save();

        $empolyee_task = new EmployeeTask();
        $empolyee_task->task_id = $task->id;
        $empolyee_task->employee_id = $data["employee_id"];
        $empolyee_task->save();

        return $task;

    }


    public function update($id,array $data)
    {

            $task =  Task::find($id);
            if(in_array("desc",array_keys($data))){
                $task->desc = $data["desc"];
            }
            if(in_array("status",array_keys($data))){
                $task->status = $data["status"];
            }
            $task->save();
            return $task;
    }

    public function get_tasks_manger($manegr_id)
    {
        $manger = Employee::find($manegr_id);
        $tasks = $manger->tasks_manger;

        return $tasks;
    }

    public function delete_task_employee($employee_id)
    {
        $employee = Employee::find($employee_id);
                   $employee->tasks()->delete();
               $employee->tasks()->detach();

        return $employee;
    }


    public function search(Request $req)
    {
        $tasks_query = Task::query()
                    ->join("employees as mangers",'mangers.id','=','tasks.manger_id')
                    ->join("employees",'employees.id','=','tasks.employee_id')
                    ->select("tasks.*");

        app(Pipeline::class)
        ->send($tasks_query)
        ->through([
            new WhereLike("tasks.desc", $req->desc),
            new WhereLike("tasks.title",$req->title),
            new Where("employees.id",$req->employee_id),
            new Where("mangers.id",$req->manger_id),
            new Where("tasks.status",$req->status),

        ])
        ->thenReturn();

        $tasks = $tasks_query->get();

        return $tasks;
    }

}
