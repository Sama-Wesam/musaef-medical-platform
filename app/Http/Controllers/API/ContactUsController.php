<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    /**
     * استقبال واستلام الرسائل من صفحة تواصل معنا
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // يمكنك إضافة منطق حفظ الرسالة أو إرسال إشعار هنا

        return response()->json([
            'status' => 'success',
            'message' => 'تم استلام رسالتك بنجاح، وسنقوم بالرد عليك في أقرب وقت.'
        ], 200);
    }
}
