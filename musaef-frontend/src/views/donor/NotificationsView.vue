<template>
  <div class="notifications-page dir-rtl bg-light-gray min-vh-100 pb-5">
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4 pt-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm max-w-900 mx-auto">

        <!-- الهيدر الداخلي -->
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3 flex-wrap gap-2">
          <div class="text-end">
            <h4 class="fw-bold text-dark mb-1 fs-5 fs-md-4">
              <i class="bi bi-bell-fill text-danger me-2"></i> مركز الإشعارات
            </h4>
            <p class="text-muted fs-8 mb-0">جميع التنبيهات وإشعارات الطوارئ الخاصة بك</p>
          </div>
          <button @click="markAllAsRead" class="btn btn-outline-secondary btn-sm rounded-3 fs-8 fw-bold">
            تحديد الكل كقراءة
          </button>
        </div>

        <!-- قائمة الإشعارات -->
        <div class="d-flex flex-column gap-3">
          <div
            v-for="item in notifications"
            :key="item.id"
            class="p-3 rounded-4 d-flex align-items-center justify-content-between gap-3 border transition-all"
            :class="item.read ? 'bg-white border-light' : 'bg-pink-light border-danger-subtle'"
          >
            <div class="d-flex align-items-center gap-3 min-w-0 text-end">
              <div class="drop-circle-icon bg-white shadow-sm flex-shrink-0">
                <i class="bi bi-droplet-fill text-danger fs-5"></i>
              </div>
              <div class="min-w-0">
                <h6 class="fw-bold text-dark mb-1 fs-7 text-truncate">{{ item.title }}</h6>
                <p class="text-secondary fs-8 mb-1 text-truncate">{{ item.message }}</p>
                <small class="text-muted fs-9 d-block">{{ item.time }}</small>
              </div>
            </div>

            <span v-if="!item.read" class="badge bg-danger rounded-pill px-2.5 py-1 fs-9 fw-bold flex-shrink-0">
              جديد
            </span>
          </div>

          <div v-if="!notifications.length" class="text-center py-5 text-muted fs-8">
            لا توجد إشعارات حالياً.
          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import donor from '@/api/donor';
import DonorHeader from '@/components/donor/DonorHeader.vue';

const notifications = ref([]);

const fetchNotifications = async () => {
  try {
    const res = await donor.getNotifications();
    if (res && res.data) {
      notifications.value = res.data.data || res.data;
    }
  } catch (err) {
    console.error('خطأ في جلب الإشعارات:', err);
  }
};

const markAllAsRead = async () => {
  try {
    await donor.markAllNotificationsAsRead();
    notifications.value.forEach(n => n.read = true);
    alert('تم تحديث جميع الإشعارات كمقروءة!');
  } catch (err) {
    console.error('خطأ في تحديث الإشعارات:', err);
  }
};

onMounted(() => {
  fetchNotifications();
});
</script>

<style scoped>
.dir-rtl { direction: rtl; font-family: Arial, sans-serif; }
.bg-light-gray { background-color: #f8fafc; }
.bg-pink-light { background-color: #fdecec; }
.border-danger-subtle { border-color: #fca5a5 !important; }
.max-w-900 { max-width: 900px; }
.drop-circle-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
</style>
