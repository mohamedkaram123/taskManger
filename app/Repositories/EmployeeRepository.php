<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Filters\QueryFiltersClasses\Where;
use App\Models\Filters\QueryFiltersClasses\WhereLike;
use App\Repositories\Contract\EmployeeContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Pipeline\Pipeline;

class EmployeeRepository implements EmployeeContract
{


    public function create(array $data)
    {
        $employee = new Employee();
        $employee->user_id = $data["user_id"];
        if(in_array("manger_id",array_keys($data)) && $data["manger_id"] != ""){
            $employee->manger_id = $data["manger_id"];
        }
        $employee->department_id = $data["department_id"];
        $employee->manger = $data["user_type"] == "manger"?1:0;
        $employee->salary = $data["salary"];
        $employee->avatar = save_image($data["avatar"]) ;

        $employee->save();

        return $employee;

    }


    public function update($id,array $data)
    {
        $employee =  Employee::find($id);
        //$employee->user_id = $data["user_id"];
        if(in_array("manger_id",array_keys($data)) && $data["manger_id"] != ""){
            $employee->manger_id = $data["manger_id"];
        }
        $employee->department_id = $data["department_id"];
        $employee->manger = $data["user_type"] == "manger"?1:0;
        $employee->salary = $data["salary"];
        $employee->avatar = save_image($data["avatar"]) ;

        $employee->save();

        return $employee;

    }

    public function delete($id)
    {
        $employee =  Employee::find($id);
        $employee->delete();
        return $employee;

    }

    public function search(Request $req)
    {
        $employee_query = Employee::query()
                    ->join("users",'users.id','=','employees.user_id')
                    ->select("users.*","employees.*","users.id as user_id");

        app(Pipeline::class)
        ->send($employee_query)
        ->through([
            new WhereLike("users.first_name", $req->name),
            // new WhereLike("users.last_name", $req->last_name),
            new WhereLike("users.email", $req->email),
            new WhereLike("employees.salary",$req->salary),
            new Where("employees.department_id",$req->department),
            new Where("employees.manger_id",$req->manger),

        ])
        ->thenReturn();

        //->skip($req->skip??0)->limit($req->limit??15)
        $employee = $employee_query->get();

        return $employee;
    }

    public function mangersOption()
    {
        $mangers = Employee::where("manger",1)->get();
        return $mangers;
    }

    public function employeesOption()
    {
        $employees = Employee::where("manger",0)->get();
        return $employees;
    }

    public function tasks()
    {
        $employee = auth("employee")->user();
        return $employee->tasks;
    }

    public function employees(Request $req)
    {
        $employee = auth("employee")->user();
        $employee_query = Employee::query()
        ->join("users",'users.id','=','employees.user_id')
        ->where("employees.manger_id",$employee->id)
        ->select("employees.*");

                app(Pipeline::class)
                ->send($employee_query)
                ->through([
                new WhereLike("users.first_name", $req->name),
                // new WhereLike("users.last_name", $req->last_name),
                new WhereLike("users.email", $req->email),
                new WhereLike("employees.salary",$req->salary)
                ])
                ->thenReturn();
        $employees = $employee_query->get();
        return $employees;
    }
}
