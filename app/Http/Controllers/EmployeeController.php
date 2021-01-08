<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\ReorderEmployeeRequest;
use App\Http\Requests\SetDateEmployeeRequest;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    protected EmployeeService $service;

    /**
     * 
     * @param EmployeeService $service 
     * @return void 
     */
    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    /**
     * 
     * @param Request $request 
     * @return array 
     */
    public function index(Request $request)
    {
        $employees = $this->service->list();
        return EmployeeResource::collection($employees)->values()->all();
    }

    /**
     * 
     * @param CreateEmployeeRequest $request 
     * @return array 
     * @throws ValidationException 
     * @throws BindingResolutionException 
     */
    public function create(CreateEmployeeRequest $request)
    {
        $validated = $request->validated();
        $employee = $this->service->create(
            $validated['name'],
            $validated['line_id']
        );
        return EmployeeResource::make($employee)->resolve();
    }

    /**
     * 
     * @param Employee $employee 
     * @return array 
     * @throws BindingResolutionException 
     * @throws Exception 
     */
    public function destroy(Employee $employee)
    {
        $data = EmployeeResource::make($employee)->resolve();
        $this->service->delete($employee);
        return $data;
    }

    /**
     * 
     * @param ReorderEmployeeRequest $request 
     * @return HttpResponse 
     * @throws ValidationException 
     * @throws BindingResolutionException 
     */
    public function reorder(ReorderEmployeeRequest $request)
    {
        $validated = $request->validated();
        $this->service->reorder($validated);
        return response()->noContent();
    }

    /**
     * 
     * @param SetDateEmployeeRequest $request 
     * @return HttpResponse 
     * @throws ValidationException 
     * @throws BindingResolutionException 
     */
    public function setDate(SetDateEmployeeRequest $request)
    {
        $validated = $request->validated();
        $this->service->setDate($validated);
        return response()->noContent();
    }
}
