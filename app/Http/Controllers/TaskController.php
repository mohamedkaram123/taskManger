<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Repositories\Contract\TaskContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    private TaskContract $taskRepositry;

    public function __construct(TaskContract $taskRepositry)
    {
        $this->taskRepositry = $taskRepositry;
    }

    public function create(Request $req)
    {
        $validate =Validator::make($req->all(),[
            'title' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'employee_id'=>'required|exists:employees,id',

        ],[
            "title.required"=>  "the title is required",
            "desc.required"=>  "the desc is required",
        ]);
        if($validate->fails()){
            return fail("error",$validate->errors());
        }
        $data = $req->all();
        $emplyee = auth("employee")->user();

        if($emplyee->user->user_type != "manger"){
            return fail("error","not allowed create exam");
        }
        $data["manger_id"] = auth("employee")->user()->id;
        $task =  $this->taskRepositry->create($data);

        return success("success",$task);
    }

    public function update($id,Request $req)
    {
        $validate =Validator::make($req->all(),[
            'desc' => 'nullable|string|max:255',
            'status'=>[Rule::in(['done', 'in_progress', 'pending'])]
        ],[
            "desc.string"=>  "the desc must be string",
        ]);
        if($validate->fails()){
            return fail("error",$validate->errors());
        }
        $data = $req->all();
        $task =  $this->taskRepositry->update($id,$data);

        return success("success",$task);
    }

    public function get_tasks_manger()
    {
        $emplyee = auth("employee")->user();

        if($emplyee->user->user_type != "manger"){
            return fail("error","not allowed get exams");
        }
        $tasks =  $this->taskRepositry->get_tasks_manger($emplyee->id);

        return success("success", TaskCollection::collection($tasks));
    }

    public function search(Request $req)
    {

        $tasks = $this->taskRepositry->search($req);
        return success("success",TaskCollection::collection($tasks) );

    }

}
