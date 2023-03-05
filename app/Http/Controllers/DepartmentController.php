<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentEmpolyeeCollection;
use App\Http\Resources\DepartmentsCollection;
use App\Http\Resources\DepartmentsOptionCollection;
use App\Repositories\Contract\DepartmentContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{

    private DepartmentContract $departmentRepositry;

    public function __construct(DepartmentContract $departmentRepositry)
    {
        $this->departmentRepositry = $departmentRepositry;

    }

    public function create(Request $req)
    {
        $validate =Validator::make($req->all(),[
            'name' => 'required|string|max:255',
        ],[
            "name.required"=>  "the name is required",
        ]);
        if($validate->fails()){
            return fail("error",$validate->errors());
        }
        $data = $req->all();
        $department =  $this->departmentRepositry->create($data);

        return success("success",$department);
    }

    public function update($id,Request $req)
    {
        $validate =Validator::make($req->all(),[
            'name' => 'required|string|max:255',
        ],[
            "name.required"=>  "the name is required",
        ]);
        if($validate->fails()){
            return fail("error",$validate->errors());
        }
        $data = $req->all();
        $department =  $this->departmentRepositry->update($id,$data);

        return success("success",$department);
    }

    public function search(Request $req)
    {

        $departments = $this->departmentRepositry->search($req);
        return success("success",DepartmentEmpolyeeCollection::collection($departments));

    }


    public function delete($id)
    {
        $data =  $this->departmentRepositry->delete($id);
        return success("success",$data);
    }

    public function departmentsOption()
    {
        $department = $this->departmentRepositry->departmentsOption();
        return success("success",DepartmentsOptionCollection::collection($department) );
    }

}
