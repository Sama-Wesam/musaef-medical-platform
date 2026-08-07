<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ], 200);
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $message
        ], 200);
    }

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
