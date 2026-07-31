<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; direction: rtl; text-align: right; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #4a5568; padding: 25px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 20px; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .btn { display: inline-block; padding: 12px 28px; background-color: #3182ce; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 15px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>إعادة تعيين كلمة المرور 🔐</h1>
        </div>
        <div class="content">
            <p>مرحباً،</p>
            <p>لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك على منصة <strong>مسعف</strong>.</p>
            <p>يمكنك تغيير كلمة المرور بالضغط على الزر أدناه:</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">إعادة تعيين كلمة المرور</a>
            </div>

            <p style="margin-top: 25px; font-size: 13px; color: #718096;">إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا الإيميل وسيظل حسابك آمناً.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} منصة مسعف الطبية
        </div>
    </div>
</body>
</html>
