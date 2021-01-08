<?php

namespace Tests\Feature\APIs;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function testList()
    {
        Employee::factory()->create();

        $response = $this->get('/employees');
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'line_id',
                'duty_date'
            ]
        ]);
    }

    public function testCreate()
    {
        $data = Employee::factory()->definition();
        $response = $this->post('/employees', $data);
        $response->assertJsonStructure([
            'id',
            'name',
            'line_id',
            'duty_date'
        ]);
        $response->assertJson([
            'name' => $data['name'],
            'line_id' => $data['line_id']
        ]);
    }

    public function testDelete()
    {
        $employee = Employee::factory()->create()->toArray();
        $data = array_intersect_key($employee, array_flip(array('id', 'name', 'line_id')));
        $response = $this->delete('/employees/'.$data['id']);
        $response->assertJsonStructure([
            'id',
            'name',
            'line_id',
            'duty_date'
        ]);
        $response->assertJson($data);
    }

    public function testReorder()
    {
        for ($cnt = 0; $cnt < 3; $cnt++)
        {
            Employee::factory()->create();
        }
        $response = $this->post('/employees/reorder', array(3, 2, 1));
        $response->assertNoContent();
    }

    public function testSetDate()
    {
        $date = Carbon::now();
        Employee::factory()->create([
            'id' => 1
        ]);
        $response = $this->post('/employees/set-date', [
            [
                'id' => 1,
                'duty_date' => $date->timestamp
            ]
        ]);
        $response->assertNoContent();
    }
}
