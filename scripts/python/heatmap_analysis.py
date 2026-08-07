import sys
import json
import folium
from folium.plugins import HeatMap

def main():
    try:
        input_data = json.loads(sys.argv[1])
        # استقبال إحداثيات الحالات (طلبات الدم) والمتبرعين
        requests = input_data.get('requests', [])
        donors = input_data.get('donors', [])

        # إنشاء خريطة مركزة على الإحداثيات العامة
        m = folium.Map(location=[31.5, 34.4], zoom_start=12)

        # إضافة طبقة حرارية للحالات (اللون الأحمر - حاجة ملحة)
        request_coords = [[r['lat'], r['lon']] for r in requests]
        HeatMap(request_coords, name="حالات الاحتياج", gradient={0.4: 'yellow', 0.65: 'orange', 1: 'red'}).add_to(m)

        # إضافة طبقة حرارية للمتبرعين (اللون الأخضر - وفرة)
        donor_coords = [[d['lat'], d['lon']] for d in donors]
        HeatMap(donor_coords, name="توزيع المتبرعين", gradient={0.4: 'lime', 0.65: 'green', 1: 'darkgreen'}).add_to(m)

        # حفظ الخريطة كملف HTML
        output_path = "public/maps/heatmap.html"
        m.save(output_path)

        print(json.dumps({"status": "success", "path": output_path}))

    except Exception as e:
        print(json.dumps({"status": "error", "message": str(e)}))

if __name__ == "__main__":
    main()
