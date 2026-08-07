import sys
import json
import numpy as np
import pandas as pd
from sklearn.ensemble import RandomForestClassifier

def main():
    try:
        input_data = json.loads(sys.argv[1])
        donors = input_data['donors']

        # تحويل البيانات إلى DataFrame
        df = pd.DataFrame(donors)

        # الميزات التي تؤثر على قرار الاستجابة
        # response_rate: نسبة الردود (0.0 - 1.0)
        # last_login_days: عدد الأيام منذ آخر دخول
        # ignore_count: عدد مرات تجاهل الإشعارات السابقة

        # محاكاة نموذج تدريب (في الواقع، ستستخدمين بياناتك التاريخية الحقيقية من قاعدة البيانات)
        # هنا نفترض أن المتبرع الذي لديه نسبة رد عالية، ودخول حديث، وتجاهل قليل هو "متوقع الاستجابة"
        X = df[['response_rate', 'last_login_days', 'ignore_count']]

        # نموذج مدرب مسبقاً (هنا تبسيط للنموذج)
        # logic: إذا كان (response_rate > 0.5) و (ignore_count < 3) -> يستجيب
        df['prediction'] = ((df['response_rate'] > 0.5) & (df['ignore_count'] < 3)).astype(int)

        # إعادة المتبرعين المتوقع استجابتهم فقط
        active_donors = df[df['prediction'] == 1].to_dict(orient='records')

        print(json.dumps(active_donors, ensure_ascii=False))

    except Exception as e:
        print(json.dumps({'error': str(e)}, ensure_ascii=False))

if __name__ == "__main__":
    main()
