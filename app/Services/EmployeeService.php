<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepo;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class EmployeeService
{
    protected EmployeeRepo $repo;

    /**
     * @param EmployeeRepo $repo
     */
    public function __construct(EmployeeRepo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * 
     * @return Collection<mixed, Employee> 
     */
    public function list()
    {
        return $this->repo->getEmployees()->sortBy('order');
    }

    /**
     * 
     * @param string $name 
     * @param string $line_id 
     * @return Employee 
     * @throws InvalidArgumentException 
     */
    public function create(string $name, string $line_id)
    {
        $employee = new Employee();
        $employee->name = $name;
        $employee->line_id = $line_id;
        $employee->order = Employee::all()->max('order') + 1;
        $employee->save();
        return $employee;
    }

    /**
     * 
     * @param Employee $employee 
     * @return void 
     * @throws Exception 
     */
    public function delete(Employee $employee)
    {
        $employee->delete();
    }

    /**
     * 
     * @param array $ids 
     * @return void 
     */
    public function reorder(array $ids)
    {
        foreach ($ids as $order => $id)
        {
            Employee::where('id', $id)->update(['order' => $order + 1]);
        }
    }

    /**
     * 
     * @param array $data 
     * @return void 
     */
    public function setDate(array $data)
    {
        foreach ($data as $entry)
        {
            Employee::where('id', $entry['id'])->update(['duty_date' => Carbon::parse($entry['duty_date'])]);
        }
    }
}
