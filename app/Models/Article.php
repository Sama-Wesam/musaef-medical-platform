<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // تحديد اسم الجدول (اختياري إذا كان بنفس اسم المودل بالجمع)
    protected $table = 'articles';

    // الحقول المسموح بتعبئتها (Mass Assignment)
    protected $fillable = [
        'title',
        'content',
        'image_path',
        'is_published',
    ];

    // تحويل نوع البيانات (Casting) لتسهيل التعامل معها
    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}