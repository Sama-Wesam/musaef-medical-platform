<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use App\Http\Resources\ArticleResource; 

class EducationController extends Controller
{
    /**
     * عرض جميع المقالات لمدير النظام (مقسمة ومحسنة للإنترنت الضعيف).
     */
    public function index()
    {
        // استخدام paginate بدلاً من get لمنع تحميل آلاف السجلات دفعة واحدة وتوفير الشبكة
        $articles = Article::latest()->paginate(10);

        // إرجاع مصفوفة البيانات مصفاة ومصحوبة بمعلومات الصفحات (Links & Meta) تلقائياً
        return ArticleResource::collection($articles);
    }

    /**
     * إنشاء مقال جديد في دليل التبرع والإرشادات الطبية
     */
    public function store(Request $request)
    {
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

        $article = Article::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم إضافة المقال بنجاح.',
            'data' => new ArticleResource($article) // تمرير الكائن الجديد عبر الـ Resource لتوحيد الهيكلية
        ], 201);
    }

    /**
     * عرض تفاصيل مقال محدد لمدير النظام.
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => new ArticleResource($article) // تنقية بيانات المقال المفرد وتمريره للفرونت اند
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
            'data' => new ArticleResource($article)
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
