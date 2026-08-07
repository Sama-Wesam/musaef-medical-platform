import sys
import json
import numpy as np

def calculate_distance(lat1, lon1, lat2, lon2):
    """حساب المسافة الدقيقة بين المستشفى الحالي والمرافق الأخرى"""
    R = 6371.0
    lat1, lon1, lat2, lon2 = map(np.radians, [lat1, lon1, lat2, lon2])
    dlat = lat2 - lat1
    dlon = lon2 - lon1
    a = np.sin(dlat/2.0)**2 + np.cos(lat1) * np.cos(lat2) * np.sin(dlon/2.0)**2
    c = 2 * np.arcsin(np.sqrt(a))
    return R * c

def main():
    try:
        # استقبال بيانات المستشفى الحالي والمرافق الأخرى من Laravel
        input_data = json.loads(sys.argv[1])
        requesting_hospital = input_data['requesting_hospital']
        facilities = input_data['facilities']
        blood_type = input_data['blood_type']

        results = []

        # تحليل كل مرفق طبي (مستشفى أو بنك دم)
        for facility in facilities:
            # تجاهل المرافق التي لا تملك مخزوناً
            if facility['available_units'] > 0:

                # حساب المسافة وسرعة الوصول (ETA)
                dist = calculate_distance(
                    requesting_hospital['latitude'], requesting_hospital['longitude'],
                    facility['latitude'], facility['longitude']
                )
                eta_minutes = (dist / 40.0) * 60.0

                # صياغة رسالة الاقتراح الذكية بناءً على نوع المرفق
                if facility['type'] == 'بنك دم مركزي':
                    msg = f"بنك الدم المركزي يملك {facility['available_units']} وحدة من فصيلة {blood_type} (زمن الوصول التقريبي: {round(eta_minutes)} دقيقة)."
                else:
                    msg = f"أقرب مستشفى يحتوي على {blood_type} هو {facility['name']} (يتوفر {facility['available_units']} وحدة، يبعد {round(eta_minutes)} دقيقة)."

                results.append({
                    'facility_name': facility['name'],
                    'facility_type': facility['type'],
                    'available_units': facility['available_units'],
                    'distance_km': round(dist, 2),
                    'eta_minutes': round(eta_minutes, 0),
                    'recommendation_message': msg
                })

        # ترتيب النتائج من الأقرب إلى الأبعد
        results.sort(key=lambda x: x['distance_km'])

        # إرجاع أفضل 3 خيارات فقط كحد أقصى لمنع تشتيت الانتباه في الطوارئ
        print(json.dumps(results[:3], ensure_ascii=False))

    except Exception as e:
        print(json.dumps({'error': str(e)}, ensure_ascii=False))

if __name__ == "__main__":
    main()
