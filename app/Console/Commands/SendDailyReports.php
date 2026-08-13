<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Donation;
use App\Models\BloodRequest;
use App\Models\Notification;
use App\Services\FCMService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendDailyReports extends Command
{
    /**
     * @var string
     */
    protected $signature = 'reports:send-daily';

    /**
     * @var string
     */
    protected $description = 'إرسال ملخص إحصائيات النظام اليومية للمدراء.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating daily reports...');

        $yesterday = Carbon::yesterday();

        // حساب إحصائيات الأمس
        $successfulDonations = Donation::whereDate('created_at', $yesterday)->where('status', 'successful')->count();
        $newEmergencies = BloodRequest::whereDate('created_at', $yesterday)->count();
        $resolvedEmergencies = BloodRequest::whereDate('updated_at', $yesterday)->where('status', 'completed')->count();

        // جلب جميع المدراء في النظام
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('لم يتم العثور على أي مدراء لإرسال التقارير إليهم.');
            return 0;
        }

        $title = '📊 التقرير اليومي لمنصة مسعف';
        $reportMessage = "ملخص أداء الأمس:\n"
                       . "- طلبات طوارئ جديدة: {$newEmergencies}\n"
                       . "- حالات تم إنقاذها (مكتملة): {$resolvedEmergencies}\n"
                       . "- عمليات تبرع ناجحة: {$successfulDonations}";

        foreach ($admins as $admin) {
            try {
                // 1. إرسال الإشعار كإشعار داخلي في النظام
                Notification::create([
                    'user_id' => $admin->id,
                    'title'   => $title,
                    'body'    => $reportMessage,
                    'type'    => 'system',
                    'is_read' => false,
                ]);

                // 2. إرسال Push Notification للمدراء عبر Firebase FCM
                if (class_exists(FCMService::class)) {
                    app(FCMService::class)->sendToUser($admin->id, $title, $reportMessage);
                }
            } catch (\Throwable $e) {
                Log::error("FCM Send Error in SendDailyReports for Admin ID {$admin->id}: {$e->getMessage()}");
            }
        }

        $this->info('تم إرسال التقارير اليومية لجميع المدراء بنجاح.');
        return 0;
    }
}
