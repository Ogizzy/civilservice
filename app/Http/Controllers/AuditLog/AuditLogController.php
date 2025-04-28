<?php

namespace App\Http\Controllers\AuditLog;

use App\Models\User;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Http\Controllers\Controller;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user');

        if ($request->has('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('auditable_type') && $request->auditable_type !== '') {
            $query->where('auditable_type', $request->auditable_type);
        }

        $audits = $query->orderByDesc('created_at')->paginate(10);
        $users = User::select('id', 'surname', 'first_name')->get();
        $models = Audit::select('auditable_type')->distinct()->pluck('auditable_type');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }
        

        return view('admin.audit.index', compact('audits', 'users', 'models'));
    }
}
