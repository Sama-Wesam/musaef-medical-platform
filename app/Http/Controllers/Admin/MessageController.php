<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();

        return $this->successResponse($messages, 'تم جلب رسائل التواصل بنجاح');
    }

    public function show($id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFoundResponse('الرسالة غير موجودة');
        }

        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return $this->successResponse($message, 'تم جلب تفاصيل الرسالة');
    }

    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reply_content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('خطأ في التحقق من البيانات', 422, $validator->errors());
        }

        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFoundResponse('الرسالة غير موجودة');
        }

        $message->update([
            'reply_content' => $request->reply_content,
            'replied_at' => now(),
            'status' => 'replied'
        ]);

        return $this->successResponse($message, 'تم إرسال الرد بنجاح');
    }

    public function destroy($id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFoundResponse('الرسالة غير موجودة');
        }

        $message->delete();

        return $this->successResponse(null, 'تم حذف الرسالة بنجاح');
    }
}
