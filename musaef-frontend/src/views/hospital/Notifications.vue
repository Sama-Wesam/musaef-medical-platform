<template>
  <HospitalLayout>
    <div class="notifications-page container-fluid px-2 px-md-3 dir-rtl text-end">

      <!-- الهيدر الخاص بالصفحة -->
      <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
        <div>
          <h5 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-start gap-2 fs-6 fs-md-5">
            <img src="@/assets/icons/solar_bell-outline.png" alt="bell" width="24" height="24" class="header-icon" />
            <span>مركز الإشعارات والتنبيهات والذكاء الاصطناعي</span>
          </h5>
          <p class="text-muted fs-8 mb-0">
            متابعة نداءات الاستجابة الفورية، رصد الأنشطة المشبوهة (Fraud Detection AI)، وتحديثات مخزون الدم
          </p>
        </div>
      </div>

      <!-- مكون أزرار الفلترة -->
      <NotificationFilters
        v-model:selectedFilter="selectedFilter"
        @markAllAsRead="handleMarkAllAsRead"
      />

      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">جاري التحميل...</span>
        </div>
      </div>

      <!-- مكون قائمة الإشعارات -->
      <NotificationList
        v-else
        :items="filteredNotifications"
        :emptyText="emptyMessageText"
      />

    </div>
  </HospitalLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import NotificationFilters from '@/components/hospital/notifications/NotificationFilters.vue';
import NotificationList from '@/components/hospital/notifications/NotificationList.vue';
import apiClient from '@/api/axios';

const loading = ref(false);
const selectedFilter = ref('all');
const notificationsList = ref([
  {
    id: 1,
    type: 'success',
    title: 'استجابة متبرع (Response Prediction AI)',
    desc: 'قام المتبرع أحمد خالد بالاستجابة لنداء الطوارئ لفصيلة O- وهو في طريقه للمستشفى (وصول مقدر خلال 4 دقائق).',
    time: 'منذ دقيقتين',
    read: false
  },
  {
    id: 2,
    type: 'emergency',
    title: 'تحذير أمني ونشاط مشبوه (Fraud Detection AI)',
    desc: 'تم رصد محاولة متكررة لإرسال طلبات دم مكثفة من قبل جهة غير مألوفة، قام النظام بحظر الطلب مؤقتاً للمراجعة.',
    time: 'منذ 15 دقيقة',
    read: false
  },
  {
    id: 3,
    type: 'alert',
    title: 'تنبيه انخفاض مخزون الدم',
    desc: 'وصل مخزون فصيلة الدم -O إلى حد حرج (وحدتان متوفرتان فقط)، يرجى اتخاذ الإجراء اللازم.',
    time: 'منذ ساعة',
    read: true
  }
]);

const filteredNotifications = computed(() => {
  if (selectedFilter.value === 'unread') {
    return notificationsList.value.filter(n => !n.read);
  }
  if (selectedFilter.value === 'alerts') {
    return notificationsList.value.filter(n => n.type === 'emergency' || n.type === 'alert');
  }
  return notificationsList.value;
});

const emptyMessageText = computed(() => {
  if (selectedFilter.value === 'alerts') {
    return 'لا توجد تنبيهات عاجلة أو أمنية لعرضها حالياً';
  }
  return 'لا توجد إشعارات لعرضها حالياً';
});

const handleMarkAllAsRead = async () => {
  notificationsList.value.forEach(n => n.read = true);
  try {
    await apiClient.patch('/hospital/notifications/read-all');
  } catch (err) {
    console.warn('تم التحديث محلياً');
  }
};

const fetchNotifications = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/hospital/notifications');
    const data = res?.data?.data || res?.data || [];
    if (Array.isArray(data) && data.length > 0) {
      notificationsList.value = data;
    }
  } catch (err) {
    console.warn('استخدام إشعارات الذكاء الاصطناعي التجريبية التشغيلية');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchNotifications();
});
</script>

<style scoped>
.notifications-page {
  font-family: 'Cairo', sans-serif;
  direction: rtl;
  padding-bottom: 24px;
}
.fs-8 { font-size: 0.85rem; }

.header-icon {
  width: 20px;
  height: 20px;
}

@media (min-width: 768px) {
  .header-icon {
    width: 24px;
    height: 24px;
  }
}
</style>
