<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نداء طوارئ - مسعف</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; direction: rtl; text-align: right; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 6px solid #d90429; }
        .header { background-color: #fff5f5; padding: 20px; text-align: center; color: #d90429; }
        .header h1 { margin: 0; font-size: 22px; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .badge { background-color: #d90429; color: #ffffff; padding: 4px 12px; border-radius: 15px; font-size: 16px; font-weight: bold; }
        .info-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .info-table td { padding: 12px; border-bottom: 1px solid #eeeeee; }
        .btn-container { text-align: center; margin-top: 25px; }
        .btn { display: inline-block; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; margin: 5px; }
        .btn-primary { background-color: #d90429; color: #ffffff !important; }
        .btn-map { background-color: #0d6efd; color: #ffffff !important; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>🚨 نداء طوارئ عاجل - منصة مسعف</h1>
        </div>
        <div class="content">
            <p>أهلاً بك <strong>{{ $donorName }}</strong>،</p>
            <p>هناك حالة طوارئ حرجة تتطلب تدخلك السريع لإنقاذ حياة مريض:</p>

            <table class="info-table">
                <tr>
                    <td><strong>المستشفى:</strong></td>
                    <td>{{ $hospitalName }}</td>
                </tr>
                <tr>
                    <td><strong>فصيلة الدم المطلوبة:</strong></td>
                    <td><span class="badge">{{ $bloodType }}</span></td>
                </tr>
                <tr>
                    <td><strong>الوحدات المطلوبة:</strong></td>
                    <td>{{ $units }} وحدة</td>
                </tr>
                <tr>
                    <td><strong>مستوى الخطورة:</strong></td>
                    <td>{{ $level }}</td>
                </tr>
            </table>

            <div class="btn-container">
                @if(isset($googleMapsUrl) && $googleMapsUrl)
                    <a href="{{ $googleMapsUrl }}" target="_blank" class="btn btn-map">🗺️ فتح الموقع والملاحة المباشرة</a>
                @endif
                <a href="{{ $url }}" class="btn btn-primary">🩸 الاستجابة وتقديم التبرع</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} منصة مسعف الطبية - نداءات الطوارئ الفورية
        </div>
    </div>
</body>
</html>
