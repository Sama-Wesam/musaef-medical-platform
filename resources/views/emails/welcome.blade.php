<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك في منصة مسعف الطبية </title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; direction: rtl; text-align: right; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #0d6efd; padding: 25px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .badge { display: inline-block; padding: 4px 12px; background-color: #eef2f5; color: #0d6efd; border-radius: 20px; font-weight: bold; font-size: 14px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; border-top: 1px solid #e9ecef; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>منصة مسعف 🩸</h1>
        </div>
        <div class="content">
            <h2>أهلاً بك، {{ $userName }}! 👋</h2>
            <p>يسعدنا جداً انضمامك إلى منصة <strong>مسعف</strong> لتسهيل وتسريع عمليات التبرع بالدم وإنقاذ الأرواح.</p>
            <p>تم تسجيل حسابك بنجاح كـ: <span class="badge">{{ $role }}</span></p>
            <p>معاً نجعل الاستجابة لحالات الطوارئ أسرع وأكثر فاعلية.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} منصة مسعف الطبية - جميع الحقوق محفوظة.
        </div>
    </div>
</body>
</html>
