<?php

namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface DepartmentContract
{

    public function create(array $data);
    public function update($id,array $data);
    public function delete($id);
    public function search(Request $req);
    public function departmentsOption();

}
