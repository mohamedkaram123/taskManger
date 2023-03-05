<?php

namespace App\Repositories;

use App\Models\Department;
use App\Models\Filters\QueryFiltersClasses\WhereLike;
use App\Repositories\Contract\DepartmentContract;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class DepartmentRepository implements DepartmentContract
{
    public function create(array $data)
    {
        $department = new Department();
        $department->name = $data["name"];
        $department->save();

        return $department;

    }


    public function update($id,array $data)
    {
        $department = Department::find($id);
        $department->name = $data["name"];
        $department->save();

        return $department;

    }

    public function delete($id)
    {

         $department = department::find($id);
         $check_empolyees = false;
        if($department->empolyees->count() > 0){
            $check_empolyees =true;
        }else{
            $department->delete();
        }

        return ["check_empolyees"=>$check_empolyees,"department"=>$department];

    }


    public function search(Request $req)
    {
        $department_query = Department::query()->with("empolyees");

        app(Pipeline::class)
        ->send($department_query)
        ->through([
            new WhereLike("departments.name", $req->name),
        ])
        ->thenReturn();

        //->skip($req->skip??0)->limit($req->limit??10)
        $departments = $department_query->get();

        return $departments;
    }

    public function departmentsOption()
    {
        $departments = Department::get();
        return $departments;
    }

}
