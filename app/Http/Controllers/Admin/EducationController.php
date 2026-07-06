<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    /**
     * عرض جميع المقالات لمدير النظام (المنشورة وغير المنشورة).
     */
    public function index()
    {
        $articles = Article::latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $articles
        ], 200);
    }

    /**
     * إنشاء مقال جديد في المركز التعليمي.
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_path' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // إنشاء المقال
        $article = Article::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم إضافة المقال بنجاح.',
            'data' => $article
        ], 201);
    }

    /**
     * عرض تفاصيل مقال محدد لمدير النظام لتعديله.
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $article
        ], 200);
    }

    /**
     * تحديث بيانات مقال موجود.
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image_path' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $article->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المقال بنجاح.',
            'data' => $article
        ], 200);
    }

    /**
     * حذف مقال من قاعدة البيانات.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف المقال بنجاح.'
        ], 200);
    }
}