<?php

namespace App\Repositories;

use App\Models\Employee;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepo
{
    /**
     * 
     * @return Collection<mixed, Employee> 
     */
    public function getEmployees()
    {
      return Employee::all();
    }

    /**
     * 
     * @param Carbon $date 
     * @return mixed 
     * @throws InvalidFormatException 
     */
    public function getEmployeesOnDuty(Carbon $date)
    {
      return Employee::where('duty_date', Carbon::parse($date))->get();
    }
}
