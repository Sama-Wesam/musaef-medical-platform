import sys
import json
import numpy as np
import pandas as pd
from sklearn.ensemble import RandomForestRegressor

def train_model():
    """
    توليد بيانات تاريخية افتراضية لتدريب النموذج عليها
    """
    # X = [المخزون_الحالي, الاستهلاك_اليومي, الطلبات_المعلقة, الطوارئ(1/0), الموسم(1-4)]
    # y = الأيام_المتبقية_حتى_النفاد (Days until shortage)

    data = {
        'current_stock': np.random.randint(10, 500, 200),
        'daily_consumption': np.random.randint(5, 50, 200),
        'pending_requests': np.random.randint(0, 30, 200),
        'is_emergency': np.random.choice([0, 1], 200, p=[0.8, 0.2]), # 20% احتمالية طوارئ
        'season': np.random.choice([1, 2, 3, 4], 200) # 1:شتاء, 2:ربيع, 3:صيف, 4:خريف
    }
    df = pd.DataFrame(data)

    # معادلة منطقية لإنتاج النتيجة (y) لكي يتدرب عليها النموذج
    # المخزون / (الاستهلاك + الطلبات + (إذا كانت هناك طوارئ يزداد الاستهلاك بنسبة 50%))
    emergency_factor = np.where(df['is_emergency'] == 1, 1.5, 1.0)
    df['days_until_shortage'] = df['current_stock'] / ((df['daily_consumption'] + df['pending_requests'] + 1) * emergency_factor)

    X = df[['current_stock', 'daily_consumption', 'pending_requests', 'is_emergency', 'season']]
    y = df['days_until_shortage']

    # تدريب نموذج Random Forest
    model = RandomForestRegressor(n_estimators=50, random_state=42)
    model.fit(X, y)

    return model

def main():
    try:
        # استقبال البيانات من Laravel
        input_data = json.loads(sys.argv[1])
        blood_type = input_data['blood_type']

        # تدريب النموذج على البيانات الافتراضية
        model = train_model()

        # تجهيز بيانات المستشفى الحالية للتنبؤ
        current_features = pd.DataFrame([{
            'current_stock': input_data['current_stock'],
            'daily_consumption': input_data['daily_consumption'],
            'pending_requests': input_data['pending_requests'],
            'is_emergency': input_data['is_emergency'],
            'season': input_data['season']
        }])

        # التنبؤ بعدد الأيام المتبقية
        predicted_days = model.predict(current_features)[0]
        predicted_days = max(0, round(predicted_days, 1)) # تقريب الرقم ومنع القيم السالبة

        # تحديد حالة الخطر
        if predicted_days <= 3:
            status = 'حرج جداً'
            message = f"تنبيه عاجل: خلال {predicted_days} أيام سيحدث نقص حاد في فصيلة {blood_type}."
        elif predicted_days <= 7:
            status = 'تحذير'
            message = f"خلال الأسبوع القادم سيصل مخزون {blood_type} إلى مستوى منخفض."
        else:
            status = 'مستقر'
            message = f"مخزون {blood_type} آمن ويكفي لمدة {predicted_days} يوماً تقريباً."

        # إرجاع النتيجة
        result = {
            'blood_type': blood_type,
            'predicted_days': predicted_days,
            'status': status,
            'message': message
        }

        print(json.dumps(result, ensure_ascii=False))

    except Exception as e:
        print(json.dumps({'error': str(e)}))

if __name__ == "__main__":
    main()
