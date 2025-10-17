<?php

namespace App\Http\Controllers\Step;

use App\Models\Step;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StepController extends Controller
{
   /**
     * Display a listing of steps
     *
     * @return \Illuminate\Http\Response
     */
 public function index(Request $request)
{
    // Start query
    $query = Step::query();

    // Apply search filter
    if ($request->has('search') && $request->search != '') {
        $query->where('step', 'LIKE', '%'.$request->search.'%');
    }
    // Pagination
    $perPage = $request->input('per_page', 10);
    $steps = $query->orderBy('step', 'asc')->paginate($perPage);
    // Append search term to pagination links
    $steps->appends($request->query());

    return view('admin.step.index', compact('steps'));
}

    /**
     * Show the form for creating a new step
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.step.create');
    }

    /**
     * Store a newly created step in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'step' => 'required|string|max:255|unique:steps,step',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Step::create([
            'step' => $request->step,
        ]);

        $notification = array(
            'message' => 'Step created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('steps.index')
            ->with($notification);
    }

    /**
     * Display the specified step
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $step = Step::findOrFail($id);
        return view('admin.step.show', compact('step'));
    }

    /**
     * Show the form for editing the specified step
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $step = Step::findOrFail($id);
        return view('admin.step.edit', compact('step'));
    }

    /**
     * Update the specified step in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $step = Step::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'step' => 'required|string|max:255|unique:steps,step,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $step->update([
            'step' => $request->step,
        ]);

        $notification = array(
            'message' => 'Step updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('steps.index')
            ->with($notification);
    }

    /**
     * Remove the specified step from storage
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Step $step)
{
    $step->delete();

    $notification = array(
        'message' => 'Step deleted successfully',
        'alert-type' => 'success'
    );

    return redirect()->route('steps.index')->with($notification);
}


    /**
     * Search for steps by name
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $steps = Step::where('step', 'like', '%' . $query . '%')
            ->orderBy('step', 'asc')
            ->get();
            
        return view('admin.step.index', compact('steps'));
    }

    /**
     * Get steps for AJAX requests
     *
     * @return \Illuminate\Http\Response
     */
    public function getSteps()
    {
        $steps = Step::orderBy('step', 'asc')->get();
        return response()->json($steps);
    }

    /**
     * Import steps from CSV
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $data = array_map('str_getcsv', file($path));
        
        // Skip header row if it exists
        if (isset($data[0]) && count($data) > 1) {
            $header = $data[0];
            // Check if header contains 'step' field
            if (in_array('step', $header) || in_array('Step', $header)) {
                array_shift($data);
            }
        }
        
        $imported = 0;
        $duplicates = 0;
        
        foreach ($data as $row) {
            if (isset($row[0]) && !empty($row[0])) {
                $stepName = trim($row[0]);
                
                // Check if step already exists
                if (!Step::where('step', $stepName)->exists()) {
                    Step::create(['step' => $stepName]);
                    $imported++;
                } else {
                    $duplicates++;
                }
            }
        }
        
        $message = "Import completed. $imported steps imported successfully.";
        if ($duplicates > 0) {
            $message .= " $duplicates duplicates were skipped.";
        }
        
        return redirect()->route('steps.index')
            ->with('success', $message);
    }

    /**
     * Export steps to CSV
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $steps = Step::orderBy('step', 'asc')->get(['step'])->toArray();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="steps.csv"',
        ];
        
        $callback = function() use ($steps) {
            $file = fopen('php://output', 'w');
            
            // Add header row
            fputcsv($file, ['step']);
            
            // Add data rows
            foreach ($steps as $step) {
                fputcsv($file, [$step['step']]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk delete steps
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:steps,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        Step::whereIn('id', $request->ids)->delete();
        
        return response()->json(['success' => 'Steps deleted successfully']);
    }
}
