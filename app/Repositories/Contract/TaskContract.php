<?php

namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface TaskContract
{

    public function create(array $data);
    public function update($id,array $data);
    public function get_tasks_manger($id);
    public function delete_task_employee($employee_id);
    public function search(Request $req);

}
