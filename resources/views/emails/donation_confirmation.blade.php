<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شكراً لبطولتك - مسعف</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; direction: rtl; text-align: right; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #198754; padding: 25px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 22px; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .summary { background: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .points { color: #198754; font-weight: bold; font-size: 18px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>🎉 شكراً لبطولتك واستجابتك!</h1>
        </div>
        <div class="content">
            <p>عزيزنا البطل <strong>{{ $donorName }}</strong>،</p>
            <p>تم تسجيل وتأكيد عملية التبرع بالدم بنجاح. مساهمتك القيمة تمنح أملاً جديداً للحياة.</p>

            <div class="summary">
                <p><strong>المستشفى:</strong> {{ $hospitalName }}</p>
                <p><strong>عدد الوحدات المتبرع بها:</strong> {{ $units }}</p>
                <p><strong>تاريخ التبرع:</strong> {{ $date }}</p>
                <p><strong>النقاط المكتسبة:</strong> <span class="points">+{{ $pointsEarned }} نقطة</span></p>
            </div>

            <p>تمت إضافة النقاط إلى حسابك ويمكنك استبدالها عبر قائمة مكافآت المنصة. بفضل عطائك، ساهمت اليوم في إنقاذ حياة.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} منصة مسعف الطبية - معاً لتسهيل التبرع بالدم ونبضٍ ممتد
        </div>
    </div>
</body>
</html>
