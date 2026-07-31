<?php

namespace Tests\Feature;

use App\AI\FraudDetectionAI;
use App\Models\BloodType;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class EmergencyApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار قدرة المستشفى المعتمد على إنشاء طلب طوارئ جديد عبر الـ API.
     */
    public function test_verified_hospital_can_create_emergency_request(): void
    {
        // 1. محاكاة (Mock) لكلاس FraudDetectionAI لمنع استدعاء العمليات الخارجية في بيئة الاختبار
        $fraudMock = Mockery::mock('overload:' . FraudDetectionAI::class);
        $fraudMock->shouldReceive('analyzeRequest')
            ->andReturn(['is_suspicious' => false]);

        // 2. إنشاء سجل فصيلة الدم المطلوبة للـ Validation
        $bloodType = BloodType::firstOrCreate(
            ['id' => 1],
            ['name' => 'A+']
        );

        // 3. إنشاء مستخدم بحساب مستشفى معتمد
        $user = User::factory()->create([
            'role' => 'hospital',
        ]);

        $hospital = Hospital::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        // 4. إرسال طلب إنشاء نداء طوارئ عبر الـ API
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/hospital/requests', [
            'blood_type_id' => $bloodType->id,
            'units_required' => 3,
            'emergency_level' => 'critical',
        ]);

        // 5. التحقق من صحة الاستجابة
        $response->assertStatus(201)
            ->assertJsonPath('success', true);
    }
}
