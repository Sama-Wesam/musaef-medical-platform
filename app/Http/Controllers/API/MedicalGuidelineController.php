<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;

class MedicalGuidelineController extends Controller
{
    /**
     * عرض المقالات المنشورة فقط للزوار.
     */
    public function index()
    {
        // جلب المقالات المنشورة فقط وترتيبها من الأحدث للأقدم
        $articles = Article::where('is_published', true)->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $articles
        ], 200);
    }

    /**
     * عرض تفاصيل مقال محدد للزائر (بشرط أن يكون منشوراً).
     */
    public function show($id)
    {
        // البحث عن المقال، وإرجاع خطأ 404 إذا لم يكن موجوداً أو غير منشور
        $article = Article::where('is_published', true)->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $article
        ], 200);
    }
}
