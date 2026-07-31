import sys
import json
import pandas as pd

def main():
    try:
        # استقبال البيانات الشاملة من قاعدة بيانات Laravel
        input_data = json.loads(sys.argv[1])

        requests_data = input_data.get('requests', [])
        donors_data = input_data.get('donors', [])
        inventory_data = input_data.get('inventory', [])

        # تحويل البيانات إلى DataFrames لمعالجتها بمكتبة Pandas
        df_requests = pd.DataFrame(requests_data)
        df_donors = pd.DataFrame(donors_data)
        df_inventory = pd.DataFrame(inventory_data)

        analytics_result = {}

        # 1. تحليل الفصيلة الأكثر طلباً
        if not df_requests.empty and 'blood_type' in df_requests.columns:
            most_requested = df_requests['blood_type'].value_counts().idxmax()
            analytics_result['most_requested_blood_type'] = most_requested
        else:
            analytics_result['most_requested_blood_type'] = "لا توجد بيانات كافية"

        # 2. تحليل الفصيلة الأقل توفراً في المخزون
        if not df_inventory.empty and 'blood_type' in df_inventory.columns:
            # تجميع المخزون حسب الفصيلة وحساب المجموع، ثم اختيار الأقل
            lowest_stock = df_inventory.groupby('blood_type')['available_units'].sum().idxmin()
            analytics_result['lowest_available_blood_type'] = lowest_stock
        else:
            analytics_result['lowest_available_blood_type'] = "لا توجد بيانات كافية"

        # 3. تحليل المنطقة الأكثر تواجداً للمتبرعين بها
        if not df_donors.empty and 'address' in df_donors.columns:
            top_region = df_donors['address'].value_counts().idxmax()
            analytics_result['top_donor_region'] = top_region
        else:
            analytics_result['top_donor_region'] = "لا توجد بيانات كافية"

        # 4. تحليل المستشفى الأكثر استهلاكاً للدم بناءً على كميات الطلبات المكتملة
        # تم تعديل الحقل إلى units_required ليتوافق مع استعلام الباك إند في DonationAnalyticsEngine.php
        if not df_requests.empty and 'hospital_name' in df_requests.columns and 'units_required' in df_requests.columns:
            top_hospital = df_requests.groupby('hospital_name')['units_required'].sum().idxmax()
            analytics_result['top_consuming_hospital'] = top_hospital
        else:
            analytics_result['top_consuming_hospital'] = "لا توجد بيانات كافية"

        # إرجاع التقرير النهائي باللغة العربية
        print(json.dumps(analytics_result, ensure_ascii=False))

    except Exception as e:
        print(json.dumps({'error': str(e)}, ensure_ascii=False))

if __name__ == "__main__":
    main()
