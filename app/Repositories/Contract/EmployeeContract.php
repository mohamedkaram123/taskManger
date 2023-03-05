<?php

namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface EmployeeContract
{


    public function create(array $data);
    public function update($id,array $data);
    public function delete($id);
    public function search(Request $req);
    public function mangersOption();
    public function employeesOption();
    public function tasks();
    public function employees(Request $req);



}
