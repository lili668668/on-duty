<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Employee $resource
 */
class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'line_id' => $this->resource->line_id,
            'duty_date' => $this->resource->duty_date === null ? null : Carbon::parse($this->resource->duty_date)->timestamp
        ];
    }
}
