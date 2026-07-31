<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نداء طوارئ عاجل</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; direction: rtl; text-align: right; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 6px solid #d90429; }
        .header { background-color: #fff5f5; padding: 20px; text-align: center; color: #d90429; }
        .header h1 { margin: 0; font-size: 22px; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .info-box { background: #f8f9fa; border-right: 4px solid #d90429; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .btn { display: inline-block; padding: 12px 28px; background-color: #d90429; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 15px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>🚨 نداء طوارئ عاجل للدم</h1>
        </div>
        <div class="content">
            <p>عزيزي المتبرع <strong>{{ $donorName }}</strong>،</p>
            <p>هناك حالة طوارئ حرجة تتطلب تدخلك السريع لتوفير وحدات دم:</p>

            <div class="info-box">
                <p><strong>المستشفى:</strong> {{ $hospitalName }}</p>
                <p><strong>الفصيلة المطلوبة:</strong> <span style="color: #d90429; font-size: 18px; font-weight: bold;">{{ $bloodType }}</span></p>
                <p><strong>عدد الوحدات:</strong> {{ $units }}</p>
                <p><strong>درجة الخطورة:</strong> {{ $level }}</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn">الاستجابة لنداء الاستغاثة</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} منصة مسعف الطبية - نداءات الطوارئ الفورية
        </div>
    </div>
</body>
</html>
