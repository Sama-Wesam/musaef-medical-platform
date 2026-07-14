import sys
import json
import numpy as np
import pandas as pd
from datetime import datetime
from sklearn.preprocessing import MinMaxScaler

def calculate_distance_numpy(lat1, lon1, lat2, lon2):
    """حساب المسافة باستخدام NumPy لتنفيذ أسرع على مصفوفات البيانات"""
    R = 6371.0
    lat1, lon1, lat2, lon2 = map(np.radians, [lat1, lon1, lat2, lon2])
    dlat = lat2 - lat1
    dlon = lon2 - lon1
    a = np.sin(dlat/2.0)**2 + np.cos(lat1) * np.cos(lat2) * np.sin(dlon/2.0)**2
    c = 2 * np.arcsin(np.sqrt(a))
    return R * c

def calculate_age(dob_string):
    """حساب العمر بناءً على تاريخ الميلاد القادم من Laravel"""
    if not dob_string:
        return 30 # عمر افتراضي في حال عدم توفر البيانات
    dob = datetime.strptime(dob_string, '%Y-%m-%d')
    today = datetime.today()
    return today.year - dob.year - ((today.month, today.day) < (dob.month, dob.day))

def main():
    try:
        # 1. استقبال البيانات وتجهيزها
        input_data = json.loads(sys.argv[1])
        hospital = input_data['hospital']
        donors_raw = input_data['donors']
        limit = input_data.get('limit', 10)

        # تحويل البيانات إلى Pandas DataFrame لسهولة وسرعة التعامل معها
        df = pd.DataFrame(donors_raw)

        if df.empty:
            print(json.dumps([]))
            return

        # 2. تطبيق فلتر الأهلية (استبعاد غير المؤهلين فوراً)
        df = df[df['is_eligible'] == True].copy()

        if df.empty:
            print(json.dumps([]))
            return

        # 3. حساب المسافة الجغرافية وسرعة الوصول (ETA)
        df['distance_km'] = calculate_distance_numpy(
            hospital['latitude'], hospital['longitude'],
            df['latitude'].astype(float), df['longitude'].astype(float)
        )
        df['eta_minutes'] = (df['distance_km'] / 40.0) * 60.0

        # 4. حساب العمر من تاريخ الميلاد
        # (يفترض أن Laravel يرسل 'date_of_birth' في الـ JSON)
        df['age'] = df['date_of_birth'].apply(calculate_age)

        # 5. استخدام Scikit-Learn (MinMaxScaler) لتوحيد البيانات (Normalization)
        # هذا هو جوهر تعلم الآلة لضمان عدم طغيان رقم كبير (مثل المسافة) على رقم صغير (مثل عدد التبرعات)
        scaler = MinMaxScaler()

        # المعايير التي ستدخل في نموذج التقييم:
        # المسافة (أقل أفضل)، العمر (أصغر أفضل)، التبرعات السابقة (أكثر أفضل)
        # لتطبيق ذلك، نعكس المسافة والعمر بضربهما في -1 ليصبح الأكبر هو الأفضل للنموذج
        df['distance_inverted'] = df['distance_km'] * -1
        df['age_inverted'] = df['age'] * -1

        features = df[['distance_inverted', 'age_inverted', 'successful_donations']]

        # توحيد القيم لتصبح بين 0 و 1
        normalized_features = scaler.fit_transform(features)

        # 6. تحديد أوزان المعايير (Feature Weights) لإنتاج الـ Score النهائي
        # 50% للمسافة، 20% للعمر، 30% لخبرة التبرع
        weights = np.array([0.50, 0.20, 0.30])

        # ضرب القيم الموحدة في الأوزان وجمعها، ثم تحويلها لنسبة مئوية
        df['match_score'] = np.dot(normalized_features, weights) * 100

        # تقريب الأرقام
        df['match_score'] = df['match_score'].round(2)
        df['eta_minutes'] = df['eta_minutes'].round(0)

        # 7. الترتيب واختيار أفضل المتبرعين
        best_matches = df.sort_values(by='match_score', ascending=False).head(limit)

        # تجهيز المخرجات لتعود إلى Laravel
        final_results = best_matches[['donor_id', 'match_score', 'eta_minutes']].to_dict(orient='records')

        print(json.dumps(final_results))

    except Exception as e:
        print(json.dumps({'error': str(e)}))

if __name__ == "__main__":
    main()
