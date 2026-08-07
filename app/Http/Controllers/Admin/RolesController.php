<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RolesController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        return $this->successResponse(Role::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles',
            'display_name' => 'required|string',
        ]);

        $role = Role::create($validated);
        return $this->successResponse($role, 'تم إنشاء الدور بنجاح', 201);
    }
}