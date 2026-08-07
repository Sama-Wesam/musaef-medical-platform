<?php

namespace App\Services;

use App\Models\Donor;
use App\Models\HealthInfo;
use Carbon\Carbon;

class AuthService
{
    /**
     * تحليل إجابات الاستبيان الصحي وتحديد أهلية المتبرع
     *
     * @param Donor $donor
     * @param array $answers
     * @return array
     */
    public function evaluateHealthScreening(Donor $donor, array $answers)
    {
        $isEligible = true;
        $deferralDate = null;
        $message = 'أنت مؤهل فوراً للتبرع! لقد أضفنا شارة المؤهل للتبرع إلى لوحة تحكمك.';
        $status = 'eligible'; // الحالات الممكنة: eligible, suspended, deferred

        // 1. فلترة الأعراض المرضية (تعليق مؤقت بدون تاريخ محدد)
        if (isset($answers['has_symptoms']) && $answers['has_symptoms'] == true) {
            $isEligible = false;
            $status = 'suspended';
            $message = 'سلامتك تهمنا! نرجو منك التبرع حين تتحسن حالتك الصحية.';
        } 
        // 2. فلترة العمليات الجراحية أو الحمل (منع لمدة 6 أشهر)
        elseif (
            (isset($answers['had_surgery']) && $answers['had_surgery'] == true) || 
            (isset($answers['is_pregnant']) && $answers['is_pregnant'] == true)
        ) {
            $isEligible = false;
            $status = 'deferred';
            $deferralDate = Carbon::now()->addMonths(6);
            $message = 'عذراً، أنت غير مؤهل حالياً للتبرع. ننتظر انضمامك بعد 6 أشهر لضمان سلامتك.';
        }
        // 3. الأدوية (لا تمنع الأهلية فوراً ولكن قد تتطلب مراجعة)
        elseif (isset($answers['takes_medication']) && $answers['takes_medication'] == true) {
            // يمكن إضافة شارة مختلفة هنا أو إبقاءه مؤهلاً مع ملاحظة للمستشفى
            $status = 'eligible_with_review';
        }

        // تحديث بيانات المتبرع أو السجل الصحي في قاعدة البيانات
        $donor->update([
            'is_eligible' => $isEligible,
            'eligibility_status' => $status,
            'deferral_date' => $deferralDate,
        ]);

        // حفظ تفاصيل الاستبيان الصحي
        HealthInfo::updateOrCreate(
            ['donor_id' => $donor->id],
            [
                'has_symptoms' => $answers['has_symptoms'] ?? false,
                'had_surgery' => $answers['had_surgery'] ?? false,
                'takes_medication' => $answers['takes_medication'] ?? false,
                'is_pregnant' => $answers['is_pregnant'] ?? false,
            ]
        );

        return [
            'is_eligible' => $isEligible,
            'status' => $status,
            'message' => $message,
            'deferral_date' => $deferralDate ? $deferralDate->format('Y-m-d') : null,
        ];
    }
}