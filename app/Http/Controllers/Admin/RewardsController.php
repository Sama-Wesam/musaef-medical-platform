<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reward;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RewardsController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        return $this->successResponse(Reward::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer',
            'icon_path' => 'nullable|string'
        ]);

        $reward = Reward::create($validated);
        return $this->successResponse($reward, 'تم إضافة المكافأة بنجاح', 201);
    }

    public function destroy($id)
    {
        $reward = Reward::find($id);
        if ($reward) {
            $reward->delete();
            return $this->successResponse(null, 'تم حذف المكافأة');
        }
        return $this->notFoundResponse();
    }
}