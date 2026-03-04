<?php

namespace App\Http\Resources\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
 

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_number' => $this->employee_number,
            'name' => [
                'surname' => $this->surname,
                'first' => $this->first_name,
                'middle' => $this->middle_name,
                'full' => trim($this->surname . ' ' . $this->first_name . ' ' . $this->middle_name)
            ],
            'contact' => [
                'email' => $this->email,
                'phone' => $this->phone
            ],
            'job_details' => [
                'rank' => $this->rank,
                'mda' => $this->mda?->mda,
                'level' => $this->gradeLevel?->level,
                'step' => $this->step?->step,
                'paygroup' => $this->paygroup?->paygroup
            ],
            'profile_image' => $this->passport ? asset('storage/' . $this->passport) : null,
        ];
    }

    // Use the same resource in multiple places
public function employeeProfile(Request $request)
{
    return response()->json([
        'status' => 'success',
        'data' => new EmployeeProfileResource($request->user()->load([
            'mda:id,mda',
            'gradeLevel:id,level',
            'step:id,step',
            'paygroup:id,paygroup'
        ]))
    ]);
}

// Another endpoint reusing same resource
public function anotherEndpoint(Employee $employee)
{
    return new EmployeeProfileResource($employee);
}

}
