<template>
  <section class="verification-card">
    <!-- عنوان القسم -->
    <div class="section-heading">
      <i class="bi bi-patch-check"></i>

      <h2>حالة اعتماد المستشفى</h2>
    </div>

    <!-- حالة الاعتماد -->
    <div class="approval-status">
      <div class="approval-circle">
        <i class="bi bi-shield-check"></i>

        <strong>معتمد رسمياً</strong>
      </div>

      <div class="approval-details">
        <div class="detail-item">
          <span class="detail-icon">
            <i class="bi bi-building"></i>
          </span>

          <div>
            <h3>الجهة المعتمدة</h3>

            <p>وزارة الصحة / بنك الدم المركزي</p>
          </div>
        </div>

        <div class="detail-item">
          <span class="detail-icon">
            <i class="bi bi-calendar-check"></i>
          </span>

          <div>
            <h3>تاريخ آخر مراجعة</h3>

            <p>15 يونيو 2026</p>
          </div>
        </div>
      </div>
    </div>

    <!-- الوثائق المرفوعة -->
    <div class="documents-section">
      <div class="documents-heading">
        <i class="bi bi-clipboard2-check"></i>

        <h2>الوثائق المرفوعة</h2>
      </div>

      <div class="documents-grid">
        <article
          v-for="document in documents"
          :key="document.id"
          class="document-card"
        >
          <div class="document-top">
            <div class="document-info">
              <span class="pdf-icon">
                <i class="bi bi-file-earmark-pdf"></i>
              </span>

              <div>
                <h3>{{ document.name }}</h3>

                <p>{{ document.size }}</p>
              </div>
            </div>
          </div>

          <div class="document-actions">
            <span class="status-badge">
              {{ document.status }}
            </span>

            <button
              type="button"
              class="download-button"
              :aria-label="`تحميل ${document.name}`"
              @click="downloadDocument(document)"
            >
              <i class="bi bi-download"></i>
            </button>
          </div>
        </article>
      </div>
    </div>

    <!-- الأزرار -->
    <div class="form-actions">
      <button
        type="button"
        class="update-documents-button"
        :disabled="loading"
        @click="updateDocuments"
      >
        <span
          v-if="loading"
          class="spinner-border spinner-border-sm"
        ></span>

        <span>
          {{ loading ? 'جارٍ التحديث...' : 'تحديث المستندات الرسمية' }}
        </span>
      </button>

      <button
        type="button"
        class="review-history-button"
        :disabled="loading"
        @click="showReviewHistory"
      >
        عرض سجل المراجعات
      </button>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const loading = ref(false)

const documents = ref([
  {
    id: 1,
    name: 'اعتماد بنك الدم المركزي',
    size: '1.5MB',
    status: 'اكتمل المستند',
  },
  {
    id: 2,
    name: 'شهادة المطابقة والسلامة',
    size: '1.5MB',
    status: 'اكتمل المستند',
  },
  {
    id: 3,
    name: 'ترخيص المنشأة الطبية',
    size: '1.5MB',
    status: 'اكتمل المستند',
  },
  {
    id: 4,
    name: 'شهادة جودة بنك الدم',
    size: '1.5MB',
    status: 'اكتمل المستند',
  },
])

const downloadDocument = (document) => {
  console.log('تحميل المستند:', document)

  window.alert(`سيتم تحميل: ${document.name}`)
}

const updateDocuments = async () => {
  loading.value = true

  try {
    await new Promise((resolve) => {
      setTimeout(resolve, 700)
    })

    window.alert('تم تحديث المستندات الرسمية بنجاح')
  } catch (error) {
    console.error('فشل تحديث المستندات:', error)

    window.alert('حدث خطأ أثناء تحديث المستندات')
  } finally {
    loading.value = false
  }
}

const showReviewHistory = () => {
  window.alert('سيتم عرض سجل مراجعات الاعتماد هنا')
}
</script>

<style scoped>
.verification-card {
  padding: 22px 16px 25px;
  border: 1px solid #eceef2;
  border-radius: 14px;
  background-color: #ffffff;
}

.section-heading,
.documents-heading {
  display: flex;
  align-items: center;
  gap: 8px;
}

.section-heading {
  margin-bottom: 17px;
}

.section-heading i,
.documents-heading i {
  color: #ef4444;
  font-size: 20px;
}

.section-heading h2,
.documents-heading h2 {
  margin: 0;
  color: #111827;
  font-size: 17px;
  font-weight: 800;
}

.approval-status {
  min-height: 175px;
  padding: 20px 70px;

  display: flex;
  align-items: center;
  justify-content: center;
  gap: 70px;

  border: 1px solid #e8eaee;
  background-color: #ffffff;
}

.approval-circle {
  width: 145px;
  height: 145px;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 9px;

  border: 1px dashed #3dbb69;
  border-radius: 50%;
  background-color: #3dbb69;
  color: #ffffff;
  outline: 1px dashed #3dbb69;
  outline-offset: 12px;
}

.approval-circle i {
  font-size: 25px;
}

.approval-circle strong {
  font-size: 15px;
  font-weight: 800;
}

.approval-details {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 13px;
}

.detail-icon {
  width: 34px;
  height: 34px;

  display: grid;
  place-items: center;

  border-radius: 4px;
  background-color: #eaf8ee;
  color: #3dbb69;
}

.detail-item h3 {
  margin: 0 0 5px;
  color: #6b7280;
  font-size: 12px;
  font-weight: 700;
}

.detail-item p {
  margin: 0;
  color: #111827;
  font-size: 12px;
  font-weight: 700;
}

.documents-section {
  margin-top: 16px;
  padding: 12px 14px 22px;
  border: 1px solid #e8eaee;
}

.documents-heading {
  margin-bottom: 15px;
}

.documents-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 12px;
}

.document-card {
  min-width: 0;
  padding: 13px 10px;

  border: 1px solid #dfe2e7;
  border-radius: 8px;
  background-color: #ffffff;
}

.document-info {
  display: flex;
  align-items: flex-start;
  gap: 9px;
}

.pdf-icon {
  color: #ef4444;
  font-size: 28px;
}

.document-info h3 {
  margin: 0 0 6px;
  color: #111827;
  font-size: 11px;
  font-weight: 800;
}

.document-info p {
  margin: 0;
  color: #6b7280;
  font-size: 9px;
}

.document-actions {
  margin-top: 15px;

  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}

.status-badge {
  padding: 4px 8px;

  border-radius: 4px;
  background-color: #eaf8ee;
  color: #3dbb69;

  font-size: 9px;
  font-weight: 700;
}

.download-button {
  width: 25px;
  height: 25px;

  display: grid;
  place-items: center;

  border: 1px solid #dfe2e7;
  border-radius: 4px;
  background-color: #ffffff;
  color: #111827;

  cursor: pointer;
}

.download-button:hover {
  border-color: #ef4444;
  color: #ef4444;
}

.form-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 14px;
  direction: ltr;
}

.update-documents-button,
.review-history-button {
  min-height: 42px;
  border-radius: 4px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 800;
  cursor: pointer;
}

.update-documents-button {
  width: 215px;
  border: 1px solid #df272b;
  background-color: #df272b;
  color: #ffffff;
}

.update-documents-button:hover:not(:disabled) {
  background-color: #c91f24;
}

.review-history-button {
  width: 130px;
  border: 1px solid #dcdfe4;
  background-color: #ffffff;
  color: #111827;
}

.review-history-button:hover:not(:disabled) {
  border-color: #ef4444;
  color: #ef4444;
}

.update-documents-button:disabled,
.review-history-button:disabled {
  cursor: not-allowed;
  opacity: 0.65;
}

@media (max-width: 1000px) {
  .documents-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .approval-status {
    padding: 25px;
    gap: 45px;
  }
}

@media (max-width: 650px) {
  .approval-status {
    flex-direction: column;
    gap: 35px;
  }

  .documents-grid {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .update-documents-button,
  .review-history-button {
    width: 100%;
  }
}
</style>