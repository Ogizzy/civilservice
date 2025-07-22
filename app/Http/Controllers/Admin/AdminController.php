<?php

namespace App\Http\Controllers\Admin;

use App\Models\MDA;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
       $totalEmployees = Employee::count();
    $totalMdas = Mda::count();
    $retiredEmployees = Employee::where('retirement_date', '<=', now())->count();
    $upcomingRetirees = Employee::whereBetween('retirement_date', [now(), now()->addMonths(6)])->count();

    $maleCount = Employee::where('gender', 'Male')->count();
    $femaleCount = Employee::where('gender', 'Female')->count();

    $recentEmployees = Employee::latest()->take(5)->get();

     // Retirement per Year
     $retirementPerYear = Employee::selectRaw('YEAR(retirement_date) as year, COUNT(*) as total')
     ->groupBy('year')
     ->orderBy('year')
     ->pluck('total', 'year');

 // Gender distribution
 $genderStats = Employee::selectRaw('gender, COUNT(*) as total')
     ->groupBy('gender')
     ->pluck('total', 'gender');

 // MDA-wise population
 $mdaStats = MDA::withCount('employees')->orderBy('employees_count', 'desc')->take(10)->get();

 // Gender (Male and Female)
 $maleCount = Employee::where('gender', 'Male')->count();
 $femaleCount = Employee::where('gender', 'Female')->count();


    return view('admin.dashboard', compact(
        'totalEmployees',
        'totalMdas',
        'retiredEmployees',
        'upcomingRetirees',
        'maleCount',
        'femaleCount',
        'recentEmployees',
        'retirementPerYear', 
        'genderStats', 
        'mdaStats', 
        'maleCount', 
        'femaleCount'
    ));
       
    }

    public function AdminLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'You Logout Successfully',
            'alert-type' => 'info'
        );
 
        return redirect('login')->with($notification);
    } // End Method 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
}
