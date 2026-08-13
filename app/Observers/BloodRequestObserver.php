<?php

namespace App\Observers;

use App\Models\BloodRequest;
use App\Events\EmergencyCreated;
use App\Events\EmergencyStatusUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BloodRequestObserver
{
    /**
     * التفريغ الموحد لذاكرة التخزين المؤقت المتعلقة بالإحصائيات والحالات الطارئة
     */
    protected function clearEmergencyCaches(?int $requestId = null): void
    {
        Cache::forget('musaef_active_emergencies');
        Cache::forget('musaef_dashboard_stats');
        Cache::forget('musaef_heatmap_data');

        if ($requestId) {
            Cache::forget("musaef_emergency_{$requestId}");
        }
    }

    /**
     * التنفيذ عند إنشاء طلب دم طارئ جديد
     */
    public function created(BloodRequest $bloodRequest): void
    {
        // 1. تفريغ ذاكرة التخزين المؤقت
        $this->clearEmergencyCaches($bloodRequest->id);

        // 2. إطلاق حدث البث المباشر فوراً للواجهات (Vue.js عبر WebSockets)
        event(new EmergencyCreated($bloodRequest));

        // 3. تحميل العلاقة وقائياً لمنع استعلام N+1 ومن ثم التوثيق في السجلات
        $bloodRequest->loadMissing('bloodType');
        $bloodTypeName = $bloodRequest->bloodType->name ?? $bloodRequest->blood_type_id;

        $emergencyLevel = match (true) {
            $bloodRequest->emergency_level instanceof \UnitEnum => $bloodRequest->emergency_level->value ?? $bloodRequest->emergency_level->name,
            is_object($bloodRequest->emergency_level) && method_exists($bloodRequest->emergency_level, 'value') => $bloodRequest->emergency_level->value,
            default => (string) ($bloodRequest->emergency_level ?? 'normal'),
        };

        Log::info("Musaef Alert: New emergency blood request generated.", [
            'request_id'      => $bloodRequest->id,
            'blood_type'      => $bloodTypeName,
            'hospital_id'     => $bloodRequest->hospital_id,
            'emergency_level' => $emergencyLevel,
        ]);
    }

    /**
     * التنفيذ عند تحديث بيانات طلب الدم (مثل تغيير الحالة)
     */
    public function updated(BloodRequest $bloodRequest): void
    {
        // تفريغ الكاش لتحديث واجهات التطبيقات والفرونت إند فورياً
        $this->clearEmergencyCaches($bloodRequest->id);

        // في حال تم تغيير حالة الطلب
        if ($bloodRequest->wasChanged('status')) {
            // إطلاق حدث التحديث للواجهات ليتم تغيير حالة الطلب وألوانه فوراً دون إعادة تحميل
            event(new EmergencyStatusUpdated($bloodRequest));

            $oldStatus = $bloodRequest->getOriginal('status');
            $newStatus = $bloodRequest->status;

            $oldStatusVal = $oldStatus instanceof \UnitEnum ? ($oldStatus->value ?? $oldStatus->name) : $oldStatus;
            $newStatusVal = $newStatus instanceof \UnitEnum ? ($newStatus->value ?? $newStatus->name) : $newStatus;

            Log::info("Musaef Alert: Blood request status changed.", [
                'request_id' => $bloodRequest->id,
                'old_status' => $oldStatusVal,
                'new_status' => $newStatusVal,
            ]);
        }
    }

    /**
     * التنفيذ عند حذف طلب الدم
     */
    public function deleted(BloodRequest $bloodRequest): void
    {
        $this->clearEmergencyCaches($bloodRequest->id);

        Log::warning("Musaef Alert: Emergency blood request was deleted.", [
            'request_id' => $bloodRequest->id,
        ]);
    }

    /**
     * التنفيذ عند استعادة طلب الدم المحذوف
     */
    public function restored(BloodRequest $bloodRequest): void
    {
        $this->clearEmergencyCaches($bloodRequest->id);
    }

    /**
     * التنفيذ عند الحذف النهائي لطلب الدم من قاعدة البيانات
     */
    public function forceDeleted(BloodRequest $bloodRequest): void
    {
        $this->clearEmergencyCaches($bloodRequest->id);
    }
}
