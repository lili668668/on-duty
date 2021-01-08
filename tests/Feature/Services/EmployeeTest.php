<?php

namespace Tests\Feature\Services;

use App\Repositories\EmployeeRepo;
use App\Services\EmployeeService;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
  use RefreshDatabase;

  public function testList()
  {
    Employee::factory()->createMany([
      [ 'name' => 'test1' ],
      [ 'name' => 'test2' ]
    ]);
    $service = new EmployeeService(new EmployeeRepo());
    $result = $service->list()->map(function ($item) {
      return $item->name;
    })->values()->all();
    $this->assertEquals(array('test1', 'test2'), $result);
  }

  public function testOrderByOrder()
  {
    Employee::factory()->create([
      'order' => 3
    ]);
    Employee::factory()->create([
      'order' => 2
    ]);
    Employee::factory()->create([
      'order' => 1
    ]);
    $service = new EmployeeService(new EmployeeRepo());
    $result = $service->list()->map(function ($item) {
      return $item->order;
    })->values()->all();
    $this->assertEquals(array(1, 2, 3), $result);
  }

  public function testInsertInDb()
  {
    $data = Employee::factory()->definition();
    $service = new EmployeeService(new EmployeeRepo());
    $service->create($data['name'], $data['line_id']);
    $result = Employee::where('name', $data['name'])->get();
    $this->assertCount(1, $result);
  }

  public function testOrderIsMaxOrderAddOne()
  {
    Employee::factory()->create([
      'order' => 3
    ]);
    $data = Employee::factory()->definition();
    $service = new EmployeeService(new EmployeeRepo());
    $service->create($data['name'], $data['line_id']);
    $result = Employee::where('name', $data['name'])->get()->first()['order'];
    $this->assertEquals(4, $result);
  }

  public function testDeleteFromDb()
  {
    $employee = Employee::factory()->create([
      'name' => 'test'
    ]);
    $service = new EmployeeService(new EmployeeRepo());
    $service->delete($employee);
    $result = Employee::where('name', 'test')->get();
    $this->assertCount(0, $result);
  }

  public function testReorder()
  {
    Employee::factory()->create([
      'id' => 3,
      'order' => 3
    ]);
    Employee::factory()->create([
      'id' => 2,
      'order' => 2
    ]);
    Employee::factory()->create([
      'id' => 1,
      'order' => 1
    ]);
    $service = new EmployeeService(new EmployeeRepo());
    $service->reorder(array(3, 2, 1));
    $result = Employee::where('id', 3)->get()->first()['order'];
    $this->assertEquals(1, $result);
  }

  public function testSetDate()
  {
    $date = Carbon::now();
    Employee::factory()->create([
      'id' => 1
    ]);
    $service = new EmployeeService(new EmployeeRepo());
    $service->setDate(
      [
        [
          'id' => 1,
          'duty_date' => $date->timestamp
        ]
      ]
    );
    $result = Employee::where('id', 1)->get()->first()['duty_date'];
    $this->assertEquals($date->timestamp, Carbon::parse($result)->timestamp);
  }
}