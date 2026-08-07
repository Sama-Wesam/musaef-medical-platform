import sys
import json

def calculate_priority(request):
    base_scores = {
        'نزيف شديد': 95,
        'حادث': 90,
        'أطفال': 85,
        'عملية عاجلة': 80,
        'عملية عادية': 50
    }
    condition = request.get('condition_type', 'عملية عادية')
    score = base_scores.get(condition, 50)
    units = request.get('units_needed', 1)
    score += min(units * 1.5, 10)
    age = request.get('patient_age', 30)
    if age <= 12 or age >= 65:
        score += 5
    return min(round(score, 1), 100.0)

def classify_severity(score):
    """تصنيف الطلب بناءً على درجة الأولوية"""
    if score >= 95:
        return 'Critical'
    elif score >= 80:
        return 'High'
    elif score >= 60:
        return 'Medium'
    else:
        return 'Low'

def main():
    try:
        input_data = json.loads(sys.argv[1])
        requests = input_data.get('requests', [])

        # حساب الأولوية والتصنيف لكل طلب
        for req in requests:
            req['priority_score'] = calculate_priority(req)
            req['severity'] = classify_severity(req['priority_score']) # تم دمج التصنيف هنا

        # ترتيب الطلبات تنازلياً
        requests.sort(key=lambda x: x['priority_score'], reverse=True)

        print(json.dumps(requests, ensure_ascii=False))

    except Exception as e:
        print(json.dumps({'error': str(e)}, ensure_ascii=False))

if __name__ == "__main__":
    main()
