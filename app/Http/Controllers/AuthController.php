<?php

namespace App\Http\Controllers;

use App\Repositories\Contract\EmployeeContract;
use App\Repositories\Contract\UserContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class AuthController extends Controller
{
    private UserContract $userRepositry;
    private EmployeeContract $employeeRepositry;

    public function __construct(UserContract $userRepositry,EmployeeContract $employeeRepositry)
    {
        $this->userRepositry = $userRepositry;
        $this->employeeRepositry = $employeeRepositry;

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

        $token = Auth::attempt($credentials);
        if (!$token) {
            return fail("error",$validate->errors()->add("email","email is not exist"));

        }

        $user = Auth::user();
        $user["token"] = $user->generateNewToken()['token'];

        return success("success",$user);

    }

    public function logout()
    {
        auth("admin")->logout();
        return success("success");

    }

    // public function register(Request $request){


    //     $validate =Validator::make($request->all(),[
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => [
    //             'required',
    //             'string',
    //             'min:8',             // minimum length of 8 characters
    //             'regex:/[A-Z]/',     // contains uppercase letters
    //             'regex:/[a-z]/',     // contains lowercase letters
    //             'regex:/[0-9]/',     // contains numbers
    //             'regex:/[!@#$%^&*()\-_=+{};:,<.>]/', // contains symbols
    //         ],
    //     ],[
    //         "email.required"=>  "the email is required",
    //         "password.required"=>  "the password is requrired",
    //         "password.min"=>  "the password word musn't be 8 digits",
    //         "password.regex"=>  "the password must be content \n
    //         1- uppercase character \n
    //         2-lowercase character \n
    //         3-symbol \n
    //         4-number ",

    //     ]);
    //     if($validate->fails()){
    //         return fail("error",$validate->errors(),301);
    //     }

    //     $data = $request->all();
    //     $user = $this->userRepositry->create($data);

    //     $user["token"] = $user->generateNewToken()['token'];
    //     return success("success",$user);

    // }
}
