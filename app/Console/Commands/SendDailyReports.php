<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Donation;
use App\Models\BloodRequest;
use App\Models\Notification;
use Carbon\Carbon;

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

        $reportMessage = "📊 ملخص أداء الأمس:\n"
                       . "- طلبات طوارئ جديدة: {$newEmergencies}\n"
                       . "- حالات تم إنقاذها (مكتملة): {$resolvedEmergencies}\n"
                       . "- عمليات تبرع ناجحة: {$successfulDonations}";

        foreach ($admins as $admin) {
            // إرسال التقرير كإشعار (ويمكنك لاحقاً ربطه بـ Mail لإرساله كإيميل)
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'التقرير اليومي لمنصة مسعف',
                'body' => $reportMessage,
                'type' => 'system',
                'is_read' => false,
            ]);
        }

        $this->info('تم إرسال التقارير اليومية لجميع المدراء بنجاح.');
    }
}