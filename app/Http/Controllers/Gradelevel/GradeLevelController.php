<?php

namespace App\Http\Controllers\Gradelevel;

use App\Models\GradeLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GradeLevelController extends Controller
{
    /**
     * Display a listing of grade levels
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gradeLevels = GradeLevel::orderBy('level', 'asc')->get();
        return view('admin.grade-level.index', compact('gradeLevels'));
    }

    /**
     * Show the form for creating a new grade level
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.grade-level.create');
    }

    /**
     * Store a newly created grade level in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'required|string|max:255|unique:grade_levels,level',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        GradeLevel::create([
            'level' => $request->level,
        ]);

 $notification = array(
            'message' => 'Grade level created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('grade-levels.index')->with($notification);
    }

    /**
     * Display the specified grade level
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gradeLevel = GradeLevel::findOrFail($id);
        return view('admin.grade-level.show', compact('gradeLevel'));
    }

    /**
     * Show the form for editing the specified grade level
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gradeLevel = GradeLevel::findOrFail($id);
        return view('admin.grade-level.edit', compact('gradeLevel'));
    }

    /**
     * Update the specified grade level in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $gradeLevel = GradeLevel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'level' => 'required|string|max:255|unique:grade_levels,level,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $gradeLevel->update([
            'level' => $request->level,
        ]);

        $notification = array(
            'message' => 'Grade Level Updated successfully',
            'alert-type' => 'success'
        );


        return redirect()->route('grade-levels.index')->with($notification);
    }

    /**
     * Remove the specified grade level from storage
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gradeLevel = GradeLevel::findOrFail($id);
        $gradeLevel->delete();

        $notification = array(
            'message' => 'Grade Level Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('grade-levels.index')
            ->with($notification);
    }

    /**
     * Search for grade levels by level
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $gradeLevels = GradeLevel::where('level', 'like', '%' . $query . '%')
            ->orderBy('level', 'asc')
            ->get();
            
        return view('admin.grade-levels.index', compact('gradeLevels'));
    }

    /**
     * Get grade levels for AJAX requests
     *
     * @return \Illuminate\Http\Response
     */
    public function getGradeLevels()
    {
        $gradeLevels = GradeLevel::orderBy('level', 'asc')->get();
        return response()->json($gradeLevels);
    }

    /**
     * Import grade levels from CSV
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
            // Check if header contains 'level' field
            if (in_array('level', $header) || in_array('Level', $header)) {
                array_shift($data);
            }
        }
        
        $imported = 0;
        $duplicates = 0;
        
        foreach ($data as $row) {
            if (isset($row[0]) && !empty($row[0])) {
                $levelName = trim($row[0]);
                
                // Check if grade level already exists
                if (!GradeLevel::where('level', $levelName)->exists()) {
                    GradeLevel::create(['level' => $levelName]);
                    $imported++;
                } else {
                    $duplicates++;
                }
            }
        }
        
        $message = "Import completed. $imported grade levels imported successfully.";
        if ($duplicates > 0) {
            $message .= " $duplicates duplicates were skipped.";
        }
        
        return redirect()->route('grade-levels.index')
            ->with('success', $message);
    }

    /**
     * Export grade levels to CSV
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $gradeLevels = GradeLevel::orderBy('level', 'asc')->get(['level'])->toArray();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="grade_levels.csv"',
        ];
        
        $callback = function() use ($gradeLevels) {
            $file = fopen('php://output', 'w');
            
            // Add header row
            fputcsv($file, ['level']);
            
            // Add data rows
            foreach ($gradeLevels as $gradeLevel) {
                fputcsv($file, [$gradeLevel['level']]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk delete grade levels
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:grade_levels,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        GradeLevel::whereIn('id', $request->ids)->delete();
        
        return response()->json(['success' => 'Grade levels deleted successfully']);
    }
    
}
