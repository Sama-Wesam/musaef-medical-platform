<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end dir-rtl">
    <div class="text-center mb-4">
      <h6 class="fw-bold text-muted mb-1 fs-7">إشعارات النظام العامة</h6>
      <h5 class="fw-bold text-dark mb-4 fs-6">حالة اعتماد المستشفى</h5>

      <div class="verification-badge-wrapper position-relative mx-auto my-3 d-flex align-items-center justify-content-center">
        <div class="circle-badge bg-success rounded-circle text-white d-flex flex-column align-items-center justify-content-center shadow">
          <span class="fs-4 mb-1">🛡️</span>
          <span class="fw-bold fs-7">معتمد رسمياً</span>
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-center gap-2 gap-md-4 text-muted fs-7 mt-3 flex-wrap">
        <div class="d-flex align-items-center gap-1">
          <strong class="text-dark">الجهة المعتمدة:</strong>
          <span>وزارة الصحة / بنك الدم المركزي</span>
        </div>
        <div class="d-flex align-items-center gap-1">
          <strong class="text-dark">تاريخ آخر مراجعة:</strong>
          <span>15 يونيو 2026</span>
        </div>
      </div>
    </div>

    <div class="border-top pt-4 mb-4">
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
        <span class="text-danger fs-5">📄</span>
        <h6 class="fw-bold text-dark mb-0 fs-6">الوثائق المرفوعة</h6>
      </div>

      <div class="row g-3">
        <div v-for="doc in uploadedDocs" :key="doc.title" class="col-12 col-sm-6 col-lg-3">
          <div class="p-3 border rounded-4 bg-light-subtle text-center h-100 d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge bg-danger text-white fs-8 rounded-1 px-2 py-1 fw-bold">PDF</span>
              <button type="button" class="btn btn-link text-muted p-0 fs-7" title="تنزيل الملف" @click="downloadDoc(doc.title)">📥</button>
            </div>

            <div class="my-2">
              <h6 class="fw-bold fs-7 text-dark mb-1">{{ doc.title }}</h6>
              <small class="text-muted fs-8 d-block">{{ doc.size }}</small>
            </div>

            <div class="mt-2 pt-2 border-top-dashed">
              <span class="text-success fs-8 fw-bold d-flex align-items-center justify-content-center gap-1">
                <span>✓</span>
                <span>مكتمل المستندات</span>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3 mt-4 pt-2 border-top flex-wrap">
      <button type="button" class="btn btn-outline-secondary px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold" @click="showReviewHistory">عرض سجل المراجعات</button>
      <button type="button" class="btn btn-danger px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold" @click="updateOfficialDocuments">تحديث المستندات الرسمية</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const uploadedDocs = ref([
  { title: 'اعتماد بنك الدم المركزي', size: '1.5MB' },
  { title: 'شهادة المطابقة والسلامة', size: '1.2MB' },
  { title: 'ترخيص المنشأة الطبية', size: '1.8MB' },
  { title: 'شهادة جودة بنك الدم', size: '1.1MB' }
]);

const showReviewHistory = () => {
  alert('سجل المراجعات: جميع الاعتمادات سارية ومحدثة حتى تاريخ 15 يونيو 2026.');
};

const updateOfficialDocuments = () => {
  alert('جارٍ فتح نافذة تحديث المستندات الرسمية...');
};

const downloadDoc = (title) => {
  alert(`جاري تنزيل ملف: ${title}`);
};
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.75rem; }
.bg-light-subtle { background-color: #f8fafc; }
.circle-badge { width: 100px; height: 100px; background-color: #10b981 !important; border: 4px solid #d1fae5; }
.border-top-dashed { border-top: 1px dashed #e2e8f0; }
.dir-rtl { direction: rtl; }
</style>
