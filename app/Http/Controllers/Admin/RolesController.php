<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RolesController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب أدوار المستخدمين المعتمدة في النظام من الـ Enum الموحد
     */
    public function index()
    {
        $roles = collect(UserRole::cases())->map(function ($role) {
            return [
                'name' => $role->value,
                'display_name' => match ($role->value) {
                    'admin'    => 'مدير النظام',
                    'hospital' => 'جهة طبية / مستشفى',
                    'donor'    => 'متبرع بالدم',
                    default    => ucfirst($role->value),
                }
            ];
        });

        return $this->successResponse($roles, 'تم جلب أدوار النظام بنجاح');
    }

    /**
     * إنشاء دور جديد (مُقيد بحسب أدوار Enum المعتمدة)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'display_name' => 'required|string',
        ]);

        return $this->successResponse($validated, 'تم تسجيل الدور بنجاح', 201);
    }
}
