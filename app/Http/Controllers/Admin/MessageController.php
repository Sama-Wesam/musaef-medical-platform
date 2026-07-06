<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage; // النموذج الخاص بالرسائل
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * عرض جميع الرسائل والمحادثات لمدير النظام (القائمة الجانبية في صفحة التواصل).
     */
    public function index()
    {
        // جلب الرسائل مع بيانات المرسل وترتيبها من الأحدث للأقدم
        $messages = ContactMessage::with('sender')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ], 200);
    }

    /**
     * عرض محادثة أو رسالة محددة بكامل تفاصيلها.
     */
    public function show($id)
    {
        $message = ContactMessage::with('sender')->findOrFail($id);

        // تحديث حالة الرسالة لتصبح "مقروءة" بمجرد فتحها من قبل المدير
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $message
        ], 200);
    }

    /**
     * إرسال رد على رسالة محددة للمستخدم أو المستشفى.
     */
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reply_content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $message = ContactMessage::findOrFail($id);

        // هنا يتم حفظ الرد في قاعدة البيانات أو إرساله عبر البريد/الإشعارات حسب منطق النظام
        // كمثال: حفظ الرد وتحديث حالة الرسالة إلى "تم الرد"
        $message->update([
            'reply_content' => $request->reply_content,
            'replied_at' => now(),
            'status' => 'replied'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم إرسال الرد بنجاح.',
            'data' => $message
        ], 200);
    }

    /**
     * حذف رسالة أو محادثة.
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف الرسالة بنجاح.'
        ], 200);
    }
}