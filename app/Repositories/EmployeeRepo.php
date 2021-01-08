<?php

namespace App\Repositories;

use App\Models\Employee;
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
}
