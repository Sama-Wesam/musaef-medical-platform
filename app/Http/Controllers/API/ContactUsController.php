<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // معالجة الرسالة (حفظ في قاعدة البيانات أو إرسال إيميل)

        return response()->json([
            'message' => 'تم إرسال رسالتك بنجاح، وسنقوم بالرد عليك في أقرب وقت!'
        ], 200);
    }
}
