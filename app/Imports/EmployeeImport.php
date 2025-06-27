<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\MDA;
use App\Models\Step;
use App\Models\User;
use App\Models\State; 
use App\Models\Employee;
use App\Models\PayGroup;
use App\Models\GradeLevel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class EmployeeImport implements ToCollection, WithValidation, SkipsOnFailure, WithHeadingRow, WithChunkReading
{
    use Importable, SkipsFailures;

    // Cache for lookup data to avoid repeated database queries
    private static $mdaCache = [];
    private static $paygroupCache = [];
    private static $gradeLevelCache = [];
    private static $stepCache = [];
    private static $stateCache = [];
    private static $cacheLoaded = false;

    /**
     * Process the collection in batches
     *
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // Load cache once
        if (!self::$cacheLoaded) {
            $this->preloadLookupData();
            self::$cacheLoaded = true;
        }

        DB::transaction(function () use ($collection) {
            foreach ($collection as $index => $row) {
                $row = $row->toArray();
        
                // Generate unique email
                $email = $this->generateUniqueEmail($row);
        
                // Create user
                $user = User::create([
                    'surname'     => $row['surname'],
                    'first_name'  => $row['first_name'],
                    'employee_number'  => $row['employee_number'],
                    'email'       => $email,
                    'password'    => bcrypt('password'),
                    'role_id'     => 6,
                    'status'      => 'active',
                ]);
        
                // Convert relevant dates
                $dob = $this->safelyConvertDate($row['dob']);
                $firstAppointmentDate = $this->safelyConvertDate($row['first_appointment_date'] ?? null);
                $confirmationDate = $this->safelyConvertDate($row['confirmation_date'] ?? null);
                $presentAppointmentDate = $this->safelyConvertDate($row['present_appointment_date'] ?? null);
        
                // Calculate retirement date
                $retirementDate = $this->calculateRetirementDate($dob, $firstAppointmentDate);
        
                // Create employee record
                Employee::create([
                    'user_id'                  => $user->id,
                    'employee_number'          => $row['employee_number'],
                    'surname'                  => $row['surname'],
                    'first_name'               => $row['first_name'],
                    'middle_name'              => $row['middle_name'] ?? null,
                    'gender'                   => $row['gender'] ?? null,
                    'dob'                      => $dob,
                    'marital_status'           => $row['marital_status'] ?? null,
                    'religion'                 => $row['religion'] ?? null,
                    'lga'                      => $row['lga'] ?? null,
                    'contact_address'          => $row['contact_address'] ?? null,
                    'phone'                    => $row['phone'] ?? null,
                    'email'                    => $row['email'] ?? null,
                    'first_appointment_date'   => $firstAppointmentDate,
                    'confirmation_date'        => $confirmationDate,
                    'present_appointment_date' => $presentAppointmentDate,
                    'retirement_date'          => $retirementDate,
                    'mda_id'                   => $this->findMdaId($row['mda'] ?? ''),
                    'paygroup_id'              => isset($row['paygroup']) ? $this->findPaygroupId($row['paygroup']) : null,
                    'level_id'                 => $this->findGradeLevelId($row['level'] ?? ''),
                    'step_id'                  => $this->findStepId($row['step'] ?? ''),
                    'rank'                     => $row['rank'] ?? null,
                    'qualifications'           => $row['qualifications'] ?? null,
                    'net_pay'                  => $row['net_pay'] ?? null,
                    'state_id'                 => $this->findStateId($row['state'] ?? ''),
                    'password'                 => bcrypt('password'),
                ]);
            }
        });
        
        

    }

    /**
     * Generate unique email for user
     */
    private function generateUniqueEmail($row)
    {
        // If email is provided and not empty, use it
        if (!empty($row['email'])) {
            return $row['email'];
        }
        
        // Generate email from employee data
        $baseEmail = $row['employee_number'] . '@gmail.com';
        
        // Check if this email already exists
        $counter = 0;
        $email = $baseEmail;
        
        while (User::where('email', $email)->exists()) {
            $counter++;
            $email = $row['employee_number'] . '_' . $counter . '@gmail.com';
        }
        
        return $email;
    }

    /**
     * Set chunk size for reading large files
     */
    public function chunkSize(): int
    {
        return 100; 
    }

    /**
     * Preload all lookup data to cache (called once)
     */
    private function preloadLookupData()
    {
        // Cache all MDAs
        $mdas = Mda::all(['id', 'mda']);
        foreach ($mdas as $mda) {
            self::$mdaCache[strtolower($mda->mda)] = $mda->id;
        }

        // Cache all PayGroups
        $paygroups = PayGroup::all(['id', 'paygroup']);
        foreach ($paygroups as $paygroup) {
            self::$paygroupCache[strtolower($paygroup->paygroup)] = $paygroup->id;
        }

        // Cache all Grade Levels
        $gradeLevels = GradeLevel::all(['id', 'level']);
        foreach ($gradeLevels as $level) {
            self::$gradeLevelCache[$level->level] = $level->id;
        }

        // Cache all Steps
        $steps = Step::all(['id', 'step']);
        foreach ($steps as $step) {
            self::$stepCache[$step->step] = $step->id;
        }

        // Cache all States
        $states = State::all(['id', 'state']);
        foreach ($states as $state) {
            self::$stateCache[strtolower($state->state)] = $state->id;
        }
    }

    /**
     * Calculate retirement date based on DOB or first appointment date
     */
    private function calculateRetirementDate($dob, $firstAppointmentDate)
    {
        try {
            if ($dob) {
                return (clone $dob)->modify('+65 years');
            } elseif ($firstAppointmentDate) {
                return (clone $firstAppointmentDate)->modify('+35 years');
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error calculating retirement date: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Find MDA ID using cache
     */
    private function findMdaId($mdaName)
    {
        $key = strtolower(trim($mdaName));
        
        if (isset(self::$mdaCache[$key])) {
            return self::$mdaCache[$key];
        }
        
        Log::warning("MDA not found: {$mdaName}");
        return 1; // Default MDA ID
    }
    
    /**
     * Find PayGroup ID using cache
     */
    private function findPaygroupId($paygroupName)
    {
        $key = strtolower(trim($paygroupName));
        
        if (isset(self::$paygroupCache[$key])) {
            return self::$paygroupCache[$key];
        }
        
        Log::warning("PayGroup not found: {$paygroupName}");
        return 1; // Default PayGroup ID
    }
    
    /**
     * Find GradeLevel ID using cache
     */
    private function findGradeLevelId($level)
    {
        if (isset(self::$gradeLevelCache[$level])) {
            return self::$gradeLevelCache[$level];
        }
        
        Log::warning("Grade Level not found: {$level}");
        return 1; // Default Grade Level ID
    }
    
    /**
     * Find Step ID using cache
     */
    private function findStepId($step)
    {
        if (isset(self::$stepCache[$step])) {
            return self::$stepCache[$step];
        }
        
        Log::warning("Step not found: {$step}");
        return 1; // Default Step ID
    }

    /**
     * Find State ID using cache
     */
    private function findStateId($stateName)
    {
        if (empty($stateName)) {
            return null;
        }
        
        $key = strtolower(trim($stateName));
        
        if (isset(self::$stateCache[$key])) {
            return self::$stateCache[$key];
        }
        
        Log::warning("State not found: {$stateName}");
        return null;
    }

    /**
     * Transform date value from Excel to DateTime object safely
     */
    private function safelyConvertDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value);
            } else {
                return new \DateTime($value);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to convert date value: ' . $value . '. Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Define validation rules
     */
    public function rules(): array
    {
        return [
            'employee_number' => 'required|unique:employees,employee_number',
            'surname' => 'required',
            'first_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'first_appointment_date' => 'nullable',
            'confirmation_date' => 'nullable',
            'present_appointment_date' => 'nullable',
            'net_pay' => 'nullable|numeric',
            'level' => 'required',
            'step' => 'required',
            'mda' => 'required',
            'state' => 'nullable',
        ];
    }

}