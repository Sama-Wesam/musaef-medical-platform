<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4 border-bottom pb-3">
      <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 fs-6 fs-md-5">
        <i class="bi bi-person-vcard text-danger fs-5" :class="currentLanguage === 'ar' ? 'ms-2' : 'me-2'"></i>
        <span>{{ t('personalDataTitle') }}</span>
      </h5>
      <button
        v-if="!isEditing"
        class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 fw-bold fs-8 d-flex align-items-center gap-1"
        @click="isEditing = true"
      >
        <i class="bi bi-pencil-square"></i>
        <span>{{ t('editData') }}</span>
      </button>
    </div>

    <form @submit.prevent="saveData">
      <div class="d-flex align-items-center gap-3 gap-md-4 mb-4 p-3 bg-light-subtle rounded-4 border flex-wrap flex-sm-nowrap">
        <div class="position-relative mx-auto mx-sm-0">
          <img
            :src="avatarPreview || defaultAvatarImg"
            alt="صورة المتبرع"
            class="rounded-circle avatar-edit-img border border-3 border-danger shadow-sm"
            style="width: 70px; height: 70px; object-fit: cover;"
            @error="handleAvatarError"
          />

          <label v-if="isEditing" class="position-absolute bottom-0 bg-danger text-white rounded-circle p-1 cursor-pointer shadow-sm" :class="currentLanguage === 'ar' ? 'start-0' : 'end-0'" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;" :title="t('changeAvatar')">
            <i class="bi bi-camera fs-9"></i>
            <input type="file" class="d-none" accept="image/*" @change="handleImageUpload" />
          </label>
        </div>
        <div class="text-center text-sm-start min-w-0 flex-grow-1" :class="currentLanguage === 'ar' ? 'text-sm-end' : 'text-sm-start'">
          <h6 class="fw-bold text-dark mb-1 fs-7 text-truncate">{{ form.name || t('donor') }}</h6>
          <p class="text-muted fs-8 mb-0">{{ t('certifiedDonor') }}</p>
        </div>
      </div>

      <div class="row g-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <div class="col-12 col-md-6">
          <label class="form-label fs-8 text-dark fw-bold mb-1">{{ t('donorName') }}</label>
          <input v-model="form.name" type="text" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" :disabled="!isEditing" required />
        </div>
        <div class="col-12 col-md-6">
          <label class="form-label fs-8 text-dark fw-bold mb-1">{{ t('phoneNumber') }}</label>
          <input v-model="form.phone" type="tel" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" :disabled="!isEditing" />
        </div>
        <div class="col-12 col-md-6">
          <label class="form-label fs-8 text-dark fw-bold mb-1">{{ t('email') }}</label>
          <input v-model="form.email" type="email" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" :disabled="!isEditing" required />
        </div>
        <div class="col-12 col-md-6">
          <label class="form-label fs-8 text-dark fw-bold mb-1">{{ t('bloodTypeLabel') }}</label>
          <select v-model="form.blood_type_id" class="form-select fs-8" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" :disabled="!isEditing">
            <option v-for="type in [{id: 1, name: '+A'}, {id: 2, name: '-A'}, {id: 3, name: '+B'}, {id: 4, name: '-B'},{id: 5, name: '+AB'},{id: 6, name: '-AB'},{id: 7, name: '+O'},{id: 8, name: '-O'}]" :key="type.id" :value="type.id">
              {{ type.name }}
            </option>
          </select>
        </div>
      </div>

      <div v-if="isEditing" class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top flex-wrap" :class="currentLanguage === 'ar' ? 'flex-row' : 'flex-row'">
        <button type="button" class="btn btn-light border px-3 px-md-4 py-2 rounded-3 fw-bold fs-8 flex-fill flex-sm-grow-0" @click="cancelEdit" :disabled="isLoading">{{ t('cancel') }}</button>
        <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fw-bold fs-8 shadow-sm flex-fill flex-sm-grow-0" :disabled="isLoading">
          <span v-if="isLoading">{{ t('saving') }}</span>
          <span v-else>{{ t('saveUpdates') }}</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import apiClient from '@/api/axios';
import { useAuthStore } from '@/stores/authStore';
import defaultAvatarImg from '@/assets/images/pngtree-whatsapp-default-profile-photo-vector-png-image_17034397.webp';

const props = defineProps({
  profile: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update-profile']);
const authStore = useAuthStore();
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    personalDataTitle: 'البيانات الشخصية',
    editData: 'تعديل البيانات',
    donor: 'متبرع',
    certifiedDonor: 'متبرع معتمد في المنصة',
    donorName: 'اسم المتبرع',
    phoneNumber: 'رقم الهاتف',
    email: 'البريد الإلكتروني',
    bloodTypeLabel: 'فصيلة الدم',
    cancel: 'إلغاء',
    saving: 'جاري الحفظ...',
    saveUpdates: 'حفظ التحديثات',
    changeAvatar: 'تغيير الصورة الشخصية',
    successUpdate: 'تم تحديث البيانات الشخصية بنجاح!',
    errorUpdate: 'حدث خطأ أثناء التحديث.'
  },
  en: {
    personalDataTitle: 'Personal Data',
    editData: 'Edit Data',
    donor: 'Donor',
    certifiedDonor: 'Certified Donor on Platform',
    donorName: 'Donor Name',
    phoneNumber: 'Phone Number',
    email: 'Email',
    bloodTypeLabel: 'Blood Type',
    cancel: 'Cancel',
    saving: 'Saving...',
    saveUpdates: 'Save Updates',
    changeAvatar: 'Change Avatar',
    successUpdate: 'Personal data updated successfully!',
    errorUpdate: 'An error occurred during update.'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const isEditing = ref(false);
const isLoading = ref(false);
const selectedFile = ref(null);

const getInitialFormData = () => {
  const userObj = props.profile?.user || authStore.user || {};
  return {
    name: userObj.name || authStore.user?.name || '',
    email: userObj.email || authStore.user?.email || '',
    phone: props.profile?.phone || authStore.user?.phone || '',
    blood_type_id: props.profile?.blood_type_id || authStore.user?.blood_type_id || 1,
  };
};

const form = ref(getInitialFormData());

const getAvatarUrl = () => {
  const avatar = props.profile?.user?.avatar || authStore.user?.avatar;
  if (!avatar) return defaultAvatarImg;
  if (avatar.startsWith('http') || avatar.startsWith('blob:')) {
    return avatar;
  }
  return `http://localhost:8000/storage/${avatar}`;
};

const avatarPreview = ref(getAvatarUrl());

watch(() => props.profile, (newVal) => {
  if (newVal && Object.keys(newVal).length > 0) {
    form.value = {
      name: newVal.user?.name || authStore.user?.name || form.value.name,
      email: newVal.user?.email || authStore.user?.email || form.value.email,
      phone: newVal.phone || form.value.phone,
      blood_type_id: newVal.blood_type_id || form.value.blood_type_id,
    };
    avatarPreview.value = getAvatarUrl();
  }
}, { deep: true, immediate: true });

onMounted(() => {
  if (!form.value.name && authStore.user) {
    form.value = getInitialFormData();
    avatarPreview.value = getAvatarUrl();
  }
});

const handleImageUpload = (e) => {
  const file = e.target.files[0];
  if (file) {
    selectedFile.value = file;
    avatarPreview.value = URL.createObjectURL(file);
  }
};

const handleAvatarError = (e) => {
  e.target.src = defaultAvatarImg;
};

const cancelEdit = () => {
  isEditing.value = false;
  selectedFile.value = null;
  form.value = getInitialFormData();
  avatarPreview.value = getAvatarUrl();
};

const saveData = async () => {
  isLoading.value = true;
  try {
    const formData = new FormData();
    formData.append('name', form.value.name);
    formData.append('email', form.value.email);
    formData.append('phone', form.value.phone || '');
    formData.append('blood_type_id', form.value.blood_type_id || '');
    if (selectedFile.value) {
      formData.append('avatar', selectedFile.value);
    }

    const response = await apiClient.post('/donor/profile/update', formData);
    const responseBody = response.data || response;
    const updatedData = responseBody.data || responseBody;
    const userData = updatedData.user || updatedData;

    const bloodTypesMap = { 1: '+A', 2: '-A', 3: '+B', 4: '-B', 5: '+AB', 6: '-AB', 7: '+O', 8: '-O' };
    const selectedBloodTypeName = bloodTypesMap[form.value.blood_type_id] || '+O';

    const newAvatarUrl = userData.avatar
      ? (userData.avatar.startsWith('http') ? userData.avatar : `http://localhost:8000/storage/${userData.avatar}`)
      : avatarPreview.value;

    if (authStore.updateUserData) {
      authStore.updateUserData({
        name: form.value.name,
        email: form.value.email,
        phone: form.value.phone,
        blood_type_id: form.value.blood_type_id,
        blood_type_name: selectedBloodTypeName,
        avatar: newAvatarUrl
      });
    } else if (authStore.user) {
      authStore.user.name = form.value.name;
      authStore.user.email = form.value.email;
      authStore.user.avatar = newAvatarUrl;
    }

    selectedFile.value = null;
    isEditing.value = false;
    emit('update-profile', updatedData);
    alert(t('successUpdate'));
  } catch (error) {
    console.error('خطأ عند الحفظ:', error);
    alert(t('errorUpdate'));
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.cursor-pointer { cursor: pointer; }
.bg-light-subtle { background-color: #f8fafc; }
</style>
