<?php

namespace App\Http\Controllers\Hospital;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Hospital; // تأكدي من وجود نموذج المستشفى

class HospitalSettingsController extends Controller
{
    /**
     * عرض الملف التعريفي وإعدادات المستشفى الحالي.
     */
    public function index(Request $request)
    {
        // جلب بيانات المستشفى المرتبط بالمستخدم (المشرف) المسجل دخوله حالياً
        // نفترض أن هناك علاقة في نموذج User: $user->hospital
        $hospital = $request->user()->hospital;

        if (!$hospital) {
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم العثور على بيانات المستشفى المرتبطة بهذا الحساب.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $hospital
        ], 200);
    }

    /**
     * تحديث بيانات الملف التعريفي للمستشفى وساعات العمل.
     */
    public function update(Request $request)
    {
        $hospital = $request->user()->hospital;

        if (!$hospital) {
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم العثور على بيانات المستشفى المرتبطة بهذا الحساب.'
            ], 404);
        }

        // التحقق من صحة البيانات المرسلة من الواجهة الأمامية
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:500',
            'latitude' => 'nullable|numeric', // خط العرض للخريطة
            'longitude' => 'nullable|numeric', // خط الطول للخريطة
            'working_hours' => 'sometimes|required|string|max:255', // مثال: "24 ساعة / 7 أيام" أو مصفوفة أوقات
            'phone_number' => 'sometimes|required|string|max:20',
            'emergency_number' => 'nullable|string|max:20',
            'contact_email' => 'sometimes|required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // تحديث بيانات المستشفى
        $hospital->update($request->only([
            'name', 
            'address', 
            'latitude', 
            'longitude', 
            'working_hours', 
            'phone_number', 
            'emergency_number', 
            'contact_email'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث بيانات الجهة الطبية بنجاح.',
            'data' => $hospital
        ], 200);
    }
}