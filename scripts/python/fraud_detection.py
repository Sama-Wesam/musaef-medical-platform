import sys
import json
import pandas as pd
from sklearn.ensemble import IsolationForest

def main():
    try:
        input_data = json.loads(sys.argv[1])
        # البيانات تشمل: عدد الطلبات في ساعة، تكرار الطلب، متوسط كمية الدم
        data = pd.DataFrame(input_data['logs'])

        # تدريب النموذج لاكتشاف القيم الشاذة
        model = IsolationForest(contamination=0.05, random_state=42)

        # -1 يعني "احتيالي"، 1 يعني "طبيعي"
        data['is_fraud'] = model.fit_predict(data[['request_frequency', 'quantity', 'time_interval']])

        # تصفية الطلبات المشبوهة فقط
        fraudulent_logs = data[data['is_fraud'] == -1].to_dict(orient='records')

        print(json.dumps(fraudulent_logs, ensure_ascii=False))

    except Exception as e:
        print(json.dumps({'error': str(e)}, ensure_ascii=False))

if __name__ == "__main__":
    main()
