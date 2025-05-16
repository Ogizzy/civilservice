<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\MDA;
use App\Models\Step;
use App\Models\User;
use App\Models\State;  // Make sure to add this import
use App\Models\Employee;
use App\Models\PayGroup;
use App\Models\GradeLevel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeImport implements ToModel, WithValidation, SkipsOnFailure, WithHeadingRow
{
    use Importable, SkipsFailures;

    /**
     * Create a model from the imported row
     *
     * @param array $row
     * @return \App\Models\Employee
     */
    public function model(array $row)
    {
        $user = User::create([
            'surname' => $row['surname'],
            'first_name' => $row['first_name'],
            'email' => $row['email'],
            'password' => bcrypt('password'), // Or generate one
            'role_id' => 6, // Adjust to your 'employee' role
            'status' => 'active',
        ]);
        
        // Convert dates for calculation
        $dob = $this->safelyConvertDate($row['dob']);
        $firstAppointmentDate = $this->safelyConvertDate($row['first_appointment_date'] ?? null);
        
        // Calculate retirement date
        $retirementDate = $this->calculateRetirementDate($dob, $firstAppointmentDate);
    
        return new Employee([
            'user_id' => $user->id,
            'employee_number' => $row['employee_number'],
            'surname' => $row['surname'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'] ?? null,
            'gender' => $row['gender'],
            'dob' => $dob,
            'marital_status' => $row['marital_status'] ?? null,
            'religion' => $row['religion'] ?? null,
            'lga' => $row['lga'] ?? null,
            'contact_address' => $row['contact_address'] ?? null,
            'phone' => $row['phone'] ?? null,
            'email' => $row['email'] ?? null,
            'first_appointment_date' => $firstAppointmentDate,
            'confirmation_date' => $this->safelyConvertDate($row['confirmation_date'] ?? null),
            'present_appointment_date' => $this->safelyConvertDate($row['present_appointment_date'] ?? null),
            'retirement_date' => $retirementDate, // Add retirement date
            'mda_id' => $this->findMdaId($row['mda']),
            'paygroup_id' => isset($row['paygroup']) ? $this->findPaygroupId($row['paygroup']) : null,
            'level_id' => $this->findGradeLevelId($row['level']),
            'step_id' => $this->findStepId($row['step']),
            'rank' => $row['rank'] ?? null,
            'qualifications' => $row['qualifications'] ?? null,
            'net_pay' => $row['net_pay'] ?? null,
            'state_id' => $this->findStateId($row['state'] ?? ''), 
            'password' => bcrypt('password'), // Default password
        ]);
    }

    /**
     * Calculate retirement date based on DOB or first appointment date
     * 
     * @param \DateTime|null $dob Date of birth
     * @param \DateTime|null $firstAppointmentDate First appointment date
     * @return \DateTime|null Retirement date
     */
    private function calculateRetirementDate($dob, $firstAppointmentDate)
    {
        try {
            // If DOB is provided, retirement age is 65 years
            if ($dob) {
                return (clone $dob)->modify('+65 years');
            } 
            // If DOB not available but first appointment date is, use 35 years of service
            elseif ($firstAppointmentDate) {
                return (clone $firstAppointmentDate)->modify('+35 years');
            }
            
            // Return null if neither date is available
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error calculating retirement date: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Find MDA ID by MDA name, log an error if not found
     *
     * @param string $mdaName
     * @return int|null
     */
    private function findMdaId($mdaName)
    {
        $mda = Mda::where('mda', $mdaName)->first();
        
        if (!$mda) {
            // Try case-insensitive search
            $mda = Mda::whereRaw('LOWER(mda) = ?', [strtolower($mdaName)])->first();
        }
        
        if (!$mda) {
            Log::warning("MDA not found: {$mdaName}");
            // You might want to create a new MDA here if appropriate
            // $mda = Mda::create(['mda' => $mdaName]);
            return 1; // Default to a specific MDA ID if you have one
        }
        
        return $mda->id;
    }
    
    /**
     * Find PayGroup ID by name, log an error if not found
     *
     * @param string $paygroupName
     * @return int|null
     */
    private function findPaygroupId($paygroupName)
    {
        $paygroup = PayGroup::where('paygroup', $paygroupName)->first();
        
        if (!$paygroup) {
            // Try case-insensitive search
            $paygroup = PayGroup::whereRaw('LOWER(paygroup) = ?', [strtolower($paygroupName)])->first();
        }
        
        if (!$paygroup) {
            Log::warning("PayGroup not found: {$paygroupName}");
            return 1; // Default to a specific PayGroup ID if you have one
        }
        
        return $paygroup->id;
    }
    
    /**
     * Find GradeLevel ID by level, log an error if not found
     *
     * @param string|int $level
     * @return int|null
     */
    private function findGradeLevelId($level)
    {
        $gradeLevel = GradeLevel::where('level', $level)->first();
        
        if (!$gradeLevel) {
            Log::warning("Grade Level not found: {$level}");
            return 1; // Default to a specific Grade Level ID if you have one
        }
        
        return $gradeLevel->id;
    }
    
    /**
     * Find Step ID by step, log an error if not found
     *
     * @param string|int $step
     * @return int|null
     */
    private function findStepId($step)
    {
        $stepModel = Step::where('step', $step)->first();
        
        if (!$stepModel) {
            Log::warning("Step not found: {$step}");
            return 1; // Default to a specific Step ID if you have one
        }
        
        return $stepModel->id;
    }

    /**
     * Find State ID by state state, log an error if not found
     *
     * @param string $stateName
     * @return int|null
     */
    private function findStateId($stateName)
    {
        if (empty($stateName)) {
            return null;
        }
        
        // First try exact match
        $state = State::where('state', $stateName)->first();
        
        // If not found, try with 'state' column
        if (!$state) {
            $state = State::where('state', $stateName)->first();
        }
        
        // If still not found, try case-insensitive search on both columns
        if (!$state) {
            $state = State::whereRaw('LOWER(state) = ?', [strtolower($stateName)])->first();
        }
        
        if (!$state) {
            $state = State::whereRaw('LOWER(state) = ?', [strtolower($stateName)])->first();
        }
        
        if (!$state) {
            Log::warning("State not found: {$stateName}");
            return null; // Return null for state if not found
        }
        
        return $state->id;
    }

    /**
     * Transform date value from Excel to DateTime object safely
     *
     * @param mixed $value The date value from Excel
     * @return \DateTime|null
     */
    private function safelyConvertDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // If it's a numeric value (Excel date format)
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value);
            }
            // If it's a string date format
            else {
                return new \DateTime($value);
            }
        } catch (\Exception $e) {
            // Log the error
            Log::warning('Failed to convert date value: ' . $value . '. Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Define validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'employee_number' => 'required|unique:employees,employee_number',
            'surname' => 'required',
            'first_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',  // Removed date validation as we'll handle conversion ourselves
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'first_appointment_date' => 'nullable',  // Removed date validation
            'confirmation_date' => 'nullable',  // Removed date validation
            'present_appointment_date' => 'nullable',  // Removed date validation
            'net_pay' => 'nullable|numeric',
            'level' => 'required',
            'step' => 'required',
            'mda' => 'required',
            'state' => 'nullable',  // Added validation rule for state
        ];
    }
}