import sys
import json
import os
import folium
from folium.plugins import HeatMap

def main():
    try:
        if len(sys.argv) < 2:
            print(json.dumps({"status": "error", "message": "No input payload"}, ensure_ascii=False))
            return

        input_data = json.loads(sys.argv[1])

        requests = input_data.get('requests', [])
        donors = input_data.get('donors', [])

        # إحداثيات قطاع غزة كمركز افتراضي
        m = folium.Map(location=[31.5, 34.4], zoom_start=11)

        # إضافة الطبقة الحرارية للطلبات
        if requests:
            request_coords = [[r['lat'], r['lon']] for r in requests if 'lat' in r and 'lon' in r]
            if request_coords:
                HeatMap(request_coords, name="حالات الاحتياج", gradient={0.4: 'yellow', 0.65: 'orange', 1: 'red'}).add_to(m)

        # إضافة الطبقة الحرارية للمتبرعين
        if donors:
            donor_coords = [[d['lat'], d['lon']] for d in donors if 'lat' in d and 'lon' in d]
            if donor_coords:
                HeatMap(donor_coords, name="توزيع المتبرعين", gradient={0.4: 'lime', 0.65: 'green', 1: 'darkgreen'}).add_to(m)

        # الحفظ في مجلد public/maps المخصص
        base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
        output_dir = os.path.join(base_dir, "public", "maps")

        os.makedirs(output_dir, exist_ok=True)
        output_path = os.path.join(output_dir, "heatmap.html")

        m.save(output_path)

        print(json.dumps({"status": "success", "path": "/maps/heatmap.html"}, ensure_ascii=False))

    except Exception as e:
        print(json.dumps({"status": "error", "message": str(e)}, ensure_ascii=False))

if __name__ == "__main__":
    main()
