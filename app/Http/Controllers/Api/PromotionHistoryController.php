<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PromotionHistory;
use App\Http\Controllers\Controller;

class PromotionHistoryController extends Controller
{
    public function index(Request $request)
    {
        return PromotionHistory::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function requestPromotion(Request $request)
    {
        $request->validate([
            'current_rank'   => 'required|string',
            'requested_rank' => 'required|string',
            'justification'  => 'required|string',
        ]);

        $promotion = PromotionHistory::create([
            'user_id'        => $request->user()->id,
            'current_rank'   => $request->current_rank,
            'requested_rank' => $request->requested_rank,
            'justification'  => $request->justification,
            'status'         => 'pending',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Promotion request submitted',
            'data'    => $promotion,
        ]);
    }

    
}
