<?php

namespace App\Http\Controllers\Admin;

use App\Services\DonorService; // تأكدي من إنشاء هذا الملف
use App\Traits\ApiResponseTrait; // لنستخدم الـ Trait التي أنشأناها
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DonorManagementController extends Controller
{
    use ApiResponseTrait;

    protected $donorService;

    public function __construct(DonorService $donorService)
    {
        $this->donorService = $donorService;
    }

    /**
     * جلب قائمة المتبرعين
     */
    public function index()
    {
        $donors = $this->donorService->getAllDonors();
        return $this->successResponse($donors);
    }

    /**
     * عرض تفاصيل متبرع
     */
    public function show($id)
    {
        $donor = $this->donorService->getDonorById($id);
        if (!$donor) {
            return $this->notFoundResponse();
        }
        return $this->successResponse($donor);
    }

    /**
     * حذف متبرع (مثال على إجراء إداري)
     */
    public function destroy($id)
    {
        $deleted = $this->donorService->deleteDonor($id);
        if (!$deleted) {
            return $this->errorResponse('تعذر حذف المتبرع');
        }
        return $this->successResponse(null, 'تم حذف المتبرع بنجاح');
    }
}