<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">

    <!-- حالة الاعتماد العلوية -->
    <div class="text-center py-3 mb-4 border-bottom">
      <small class="text-muted fs-8 d-block mb-2">{{ t('systemNotif') }}</small>
      <h5 class="fw-bold text-dark mb-3 fs-6 fs-md-5">{{ t('accreditationStatusTitle') }}</h5>

      <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle p-3 mb-3 shadow-sm" style="width: 70px; height: 70px;">
        <i class="bi bi-shield-check fs-2"></i>
      </div>

      <h6 class="fw-bold text-success mb-1 fs-6">{{ t('officiallyAccredited') }}</h6>
      <small class="text-muted fs-8">
        {{ t('accreditedBy') }} | {{ t('lastReviewDate') }} 15 {{ currentLanguage === 'en' ? 'June' : 'يونيو' }} 2026
      </small>
    </div>

    <!-- قائمة الوثائق الرسمية المرفوعة -->
    <div class="mb-4">
      <h6 class="fw-bold text-dark mb-3 fs-6 d-flex align-items-center gap-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <span>📑</span>
        <span>{{ t('uploadedDocumentsTitle') }}</span>
      </h6>

      <div class="row g-3">
        <div v-for="(doc, index) in documentsList" :key="index" class="col-12 col-sm-6 col-xl-3">
          <div class="p-3 border rounded-4 bg-light-subtle h-100 d-flex flex-column justify-content-between shadow-2xs">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="fs-5">📄</span>
              <span class="badge bg-danger text-white rounded-pill px-2 py-0.5 fs-10">PDF</span>
            </div>
            <div>
              <strong class="d-block text-dark fs-8 mb-1 text-truncate">{{ translateDocTitle(doc.titleKey) }}</strong>
              <small class="text-muted fs-9 d-block mb-2">{{ doc.size }}</small>
            </div>
            <div class="pt-2 border-top border-light-subtle d-flex align-items-center justify-content-between">
              <span class="text-success fs-9 fw-bold">✓ {{ t('docCompleted') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- أزرار الإجراءات -->
    <div class="d-flex align-items-center justify-content-start gap-2 pt-3 border-top flex-wrap">
      <button type="button" class="btn btn-danger px-4 py-2 rounded-3 fs-7 fw-bold" @click="triggerUpload">
        {{ t('updateDocsBtn') }}
      </button>
      <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3 fs-7 fw-bold" @click="viewAuditLog">
        {{ t('viewAuditLogBtn') }}
      </button>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    systemNotif: 'إشعارات النظام العامة',
    accreditationStatusTitle: 'حالة اعتماد المستشفى',
    officiallyAccredited: 'معتمد رسمياً',
    accreditedBy: 'الجهة المعتمدة: وزارة الصحة / بنك الدم المركزي',
    lastReviewDate: 'تاريخ آخر مراجعة:',
    uploadedDocumentsTitle: 'الوثائق المرفوعة',
    docCompleted: 'مكتمل المستندات',
    updateDocsBtn: 'تحديث المستندات الرسمية',
    viewAuditLogBtn: 'عرض سجل المراجعات'
  },
  en: {
    systemNotif: 'General System Notifications',
    accreditationStatusTitle: 'Hospital Accreditation Status',
    officiallyAccredited: 'Officially Accredited',
    accreditedBy: 'Accrediting Body: Ministry of Health / Central Blood Bank',
    lastReviewDate: 'Last Review Date:',
    uploadedDocumentsTitle: 'Uploaded Official Documents',
    docCompleted: 'Document Verified',
    updateDocsBtn: 'Update Official Documents',
    viewAuditLogBtn: 'View Audit Log'
  }
};

const docTitleDict = {
  'اعتماد بنك الدم المركزي': 'Central Blood Bank Accreditation',
  'شهادة المطابقة والسلامة': 'Safety & Compliance Certificate',
  'ترخيص المنشأة الطبية': 'Medical Facility License',
  'شهادة جودة بنك الدم': 'Blood Bank Quality Certificate'
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const translateDocTitle = (key) => currentLanguage.value === 'en' ? (docTitleDict[key] || key) : key;

const documentsList = ref([
  { titleKey: 'اعتماد بنك الدم المركزي', size: '1.5MB' },
  { titleKey: 'شهادة المطابقة والسلامة', size: '1.2MB' },
  { titleKey: 'ترخيص المنشأة الطبية', size: '1.8MB' },
  { titleKey: 'شهادة جودة بنك الدم', size: '1.1MB' }
]);

const triggerUpload = () => {
  alert(currentLanguage.value === 'en' ? 'Opening official documents update window...' : 'جاري فتح نافذة رفع وتحديث المستندات والترخيص الطبي...');
};

const viewAuditLog = () => {
  alert(currentLanguage.value === 'en' ? 'Opening accreditation review log...' : 'جاري فتح سجل مراجعات الاعتماد والتوثيق الحكومي...');
};
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.78rem; }
.fs-9 { font-size: 0.72rem; }
.fs-10 { font-size: 0.65rem; }
.bg-light-subtle { background-color: #f8fafc; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
