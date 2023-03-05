<?php

namespace App\Http\Controllers;

use App\Http\Resources\MangerOptionsCollection;
use App\Http\Resources\UserEmployeeCollection;
use App\Models\Employee;
use App\Models\User;
use App\Repositories\Contract\EmployeeContract;
use App\Repositories\Contract\UserContract;
use App\Repositories\TaskRepositry;
use App\Rules\Base64;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;
use Closure;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    private EmployeeContract $employeeRepositry;
    private UserContract $userRepositry;
    private TaskRepositry $taskRepositry;


    public function __construct(UserContract $userRepositry,EmployeeContract $employeeRepositry,TaskRepositry $taskRepositry)
    {
        $this->employeeRepositry = $employeeRepositry;
        $this->userRepositry = $userRepositry;
        $this->taskRepositry = $taskRepositry;

    }

    public function create(Request $req)
    {

        $validate =Validator::make($req->all(),[
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'avatar'=>[ new Base64],
            'department_id'=>'required|exists:departments,id',
            'salary'=>'required|numeric',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',             // minimum length of 8 characters
                'regex:/[A-Z]/',     // contains uppercase letters
                'regex:/[a-z]/',     // contains lowercase letters
                'regex:/[0-9]/',     // contains numbers
                'regex:/[!@#$%^&*()\-_=+{};:,<.>]/', // contains symbols
            ],
        ],[
            "email.required"=>  "the email is required",
            "password.required"=>  "the password is requrired",
            "password.min"=>  "the password word musn't be 8 digits",
            "password.regex"=>  "the password must be content\n
            1- uppercase character\n
            2-lowercase character\n
            3-symbol\n
            4-number ",

        ]);

        $validate->after(function ($validator) use ($req) {
            if($req->user_type == 'employee' && $req->manger_id == ""){
                $validator->addFailure('manger_id', 'the manger is required', ['required']);
            }
        });

        if($validate->fails()){
            return fail("error",$validate->errors());
        }

        $data = $req->all();
        $user = $this->userRepositry->create($data);

        $data["user_id"]=$user->id;
        $employee = $this->employeeRepositry->create($data);

        //$employee["token"] = $employee->generateNewToken()['token'];
        return success("success",new UserEmployeeCollection($employee));


    }

    public function update($id,Request $req)
    {

        $employee = Employee::find($id);

        $validate =Validator::make($req->all(),[
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'salary'=>'required|numeric',
            'avatar'=>[ new Base64],
            'department_id'=>'required|exists:departments,id',
            'email' => ['required','string','email','max:255',
            Rule::unique('users')->where(function ($query) use ($employee) {
                return $query->where('id', '<>', $employee->user->id);
            }),
        ],
            'password' => [
                'nullable',
                'string',
                'min:8',             // minimum length of 8 characters
                'regex:/[A-Z]/',     // contains uppercase letters
                'regex:/[a-z]/',     // contains lowercase letters
                'regex:/[0-9]/',     // contains numbers
                'regex:/[!@#$%^&*()\-_=+{};:,<.>]/', // contains symbols
            ],
        ],[
            "email.required"=>  "the email is required",
            "password.min"=>  "the password word musn't be 8 digits",
            "password.regex"=>  "the password must be content\n
            1- uppercase character\n
            2-lowercase character\n
            3-symbol\n
            4-number ",

        ]);

        $validate->after(function ($validator) use ($req) {
            if($req->user_type == 'employee' && $req->manger_id == ""){
                $validator->addFailure('manger_id', 'the manger is required', ['required']);
            }
        });
        if($validate->fails()){
            return fail("error",$validate->errors());
        }

        $data = $req->all();

        $employee = $this->employeeRepositry->update($id,$data);
        $user = $this->userRepositry->update($employee->user->id,$data);

       // $employee["token"] = $employee->generateNewToken()['token'];
        return success("success",new UserEmployeeCollection($employee));
    }

    public function delete($id)
    {
        $employee = Employee::find($id);
        if($employee->manger == 1){
            if($employee->employees->count() > 0){
                return fail("you can't remove the manger has employees");

            }
        }
        $this->userRepositry->delete($employee->user->id);
        $this->taskRepositry->delete_task_employee($id);
        $employee = $this->employeeRepositry->delete($id);

        return success("success",$employee);

    }

    public function search(Request $req)
    {

        $employee = $this->employeeRepositry->search($req);
        return success("success",UserEmployeeCollection::collection($employee) );

    }

    public function mangersOption()
    {

        $employee = $this->employeeRepositry->mangersOption();
        return success("success",MangerOptionsCollection::collection($employee) );

    }

    public function employeesOption()
    {

        $employees = $this->employeeRepositry->employeesOption();
        return success("success",MangerOptionsCollection::collection($employees) );

    }
    public function login(Request $request)
    {
        $validate =Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => [
                'required',
            ],
        ],[
            "email.required"=>  "the email is required",
            "password.required"=>  "the password is requrired",
        ]);
        if($validate->fails()){
            return fail("error",$validate->errors());
        }

        $credentials = $request->only('email', 'password');
        $user = auth("user")->attempt($credentials);



        $validate->after(function ($validator) use ($user) {
            if(!$user){
              //  $validator->addFailure('email', 'the user not exist please check email or password');
                $validator->errors()->add('email', 'the user not exist please check email or password');

            }
        });

        if($validate->fails()){
            return fail("error",$validate->errors());
        }
        $user = auth("user")->user();
        $employee = $user->emplyee;

        $validate->after(function ($validator) use ($employee) {
            if(!$employee){
              //  $validator->addFailure('email', 'the user not emplyee');
              $validator->errors()->add('email',  'the user not emplyee');

            }
        });
        if($validate->fails()){
            return fail("error",$validate->errors());
        }

        $employee["token"] = $employee->generateNewToken()['token'];

        return success("success",collect($employee)->merge($employee->user));

    }

    public function logout()
    {
        auth("employee")->logout();
        return success("success");

    }


    public function tasks()
    {
        $tasks = $this->employeeRepositry->tasks();
        return success("success",$tasks);

    }

    public function employees(Request $req)
    {
        $employees = $this->employeeRepositry->employees($req);
        return success("success",UserEmployeeCollection::collection($employees));

    }

}
