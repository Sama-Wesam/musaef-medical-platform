<?php

namespace App\Services;

use App\Models\Donor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HealthScreeningService
{
    public function evaluateHealthScreening(Donor $donor, array $answers): array
    {
        return DB::transaction(function () use ($donor, $answers) {
            $isEligible = true;
            $deferralDate = null;
            $message = 'أنت مؤهل فوراً للتبرع! تم تحديث أهليتك في لوحة التحكم ونظام المطابقة المباشر.';
            $status = 'eligible';

            $hasSymptoms = $answers['has_symptoms'] ?? false;
            $hadSurgery = $answers['had_surgery'] ?? false;
            $takesMedication = $answers['takes_medication'] ?? false;
            $isPregnant = $answers['is_pregnant'] ?? false;

            if (isset($answers['answers']) && is_array($answers['answers'])) {
                $affirmativeCount = collect($answers['answers'])->filter(function ($q) {
                    return is_array($q) ? ($q['answer'] ?? false) : (bool) $q;
                })->count();

                if ($affirmativeCount >= 3) {
                    $hasSymptoms = true;
                }
            }

            if ((bool) $hasSymptoms === true) {
                $isEligible = false;
                $status = 'suspended';
                $message = 'سلامتك تهمنا! نرجو منك التبرع حين تتحسن حالتك الصحية.';
            } elseif ((bool) $hadSurgery === true || (bool) $isPregnant === true) {
                $isEligible = false;
                $status = 'deferred';
                $deferralDate = Carbon::now()->addMonths(6);
                $message = 'عذراً، أنت غير مؤهل حالياً للتبرع. ننتظر انضمامك بعد 6 أشهر لضمان سلامتك.';
            } elseif ((bool) $takesMedication === true) {
                $status = 'eligible_with_review';
            }

            $donor->update([
                'is_eligible'        => $isEligible,
                'eligibility_status' => $status,
                'deferral_date'      => $deferralDate,
            ]);

            // التصحيح: استخدام اسم الجدول الصحيح health_infos لتجنب أخطاء قاعدة البيانات
            DB::table('health_infos')->updateOrInsert(
                ['donor_id' => $donor->id],
                [
                    'has_chronic_diseases' => $hasSymptoms, // مطابقة لعمود الجدول أو حفظ الحقول المتوفرة
                    'is_eligible'          => $isEligible,
                    'updated_at'           => now(),
                ]
            );

            return [
                'is_eligible'   => $isEligible,
                'status'        => $status,
                'message'       => $message,
                'deferral_date' => $deferralDate ? $deferralDate->format('Y-m-d') : null,
            ];
        });
    }
}
