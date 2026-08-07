<?php

namespace App\Http\Controllers\Hospital;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class HospitalSettingsController extends Controller
{
    use ApiResponseTrait;

    /**
     * عرض الملف التعريفي وإعدادات المستشفى الحالي.
     */
    public function index(Request $request)
    {
        $hospital = $request->user()->hospital;

        if (!$hospital) {
            return $this->notFoundResponse('لم يتم العثور على بيانات المستشفى المرتبطة بهذا الحساب.');
        }

        // تحميل بيانات المستخدم للاستفادة من حقل الاسم الأساسي في الفرونت اند
        $hospital->load('user');

        return $this->successResponse($hospital);
    }

    /**
     * تحديث بيانات الملف التعريفي للمستشفى وساعات العمل.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $hospital = $user->hospital;

        if (!$hospital) {
            return $this->notFoundResponse('لم يتم العثور على بيانات المستشفى المرتبطة بهذا الحساب.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'working_hours' => 'sometimes|required|string|max:255',
            'phone_number' => 'sometimes|required|string|max:20',
            'emergency_number' => 'nullable|string|max:20',
            'contact_email' => 'sometimes|required|email|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('خطأ في التحقق من البيانات', 422, $validator->errors());
        }

        // 1. تحديث اسم المستخدم المشرف في جدول الحسابات الأساسي إذا تم إرساله
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }

        // 2. تحديث بيانات المستشفى الفعلية بجدول Hospitals
        $hospital->update($request->only([
            'address',
            'latitude',
            'longitude',
            'working_hours',
            'phone_number',
            'emergency_number',
            'contact_email'
        ]));

        $hospital->load('user');

        return $this->successResponse($hospital, 'تم تحديث بيانات الجهة الطبية بنجاح.');
    }
}
