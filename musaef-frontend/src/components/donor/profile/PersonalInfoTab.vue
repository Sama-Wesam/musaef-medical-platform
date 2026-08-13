<template>
  <div
    class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 font-arial"
    :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'"
  >
    <!-- الهيدر الرئيسي لزر التعديل والعنوان -->
    <div
      class="d-flex align-items-center justify-content-between mb-3 mb-md-4 border-bottom pb-3"
    >
      <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 fs-6 fs-md-5">
        <i
          class="bi bi-person-vcard text-danger fs-5"
          :class="currentLanguage === 'ar' ? 'ms-2' : 'me-2'"
        ></i>
        <span>{{ t("personalDataTitle") }}</span>
      </h5>
      <button
        v-if="!isEditing"
        class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 fw-bold fs-8 d-flex align-items-center gap-1"
        @click="startEditing"
      >
        <i class="bi bi-pencil-square"></i>
        <span>{{ t("editData") }}</span>
      </button>
    </div>

    <!-- بطاقة البروفايل العلوية (الصورة والاسم) -->
    <div
      class="p-3 bg-light rounded-4 border mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3"
    >
      <div class="d-flex align-items-center gap-3">
        <div class="position-relative">
          <img
            :src="avatarPreview"
            alt="صورة المتبرع"
            class="rounded-circle donor-profile-avatar border shadow-sm"
            @error="handleAvatarFallback"
          />
          <button
            v-if="isEditing"
            type="button"
            class="btn btn-danger btn-sm rounded-circle position-absolute bottom-0 start-0 p-1 d-flex align-items-center justify-content-center avatar-upload-btn"
            @click="triggerFileInput"
            title="تغيير الصورة"
          >
            <i class="bi bi-camera-fill fs-8"></i>
          </button>
          <input
            ref="fileInput"
            type="file"
            class="d-none"
            accept="image/jpeg,image/png,image/jpg,image/webp"
            @change="handleFileChange"
          />
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-1 fs-6">{{ form.name || "Sama Wesam" }}</h6>
          <small class="text-muted fs-8 d-block">{{ t("certifiedDonor") }}</small>
        </div>
      </div>
    </div>

    <!-- نموذج البيانات الشخصية -->
    <form @submit.prevent="saveProfileData">
      <div class="row g-3">
        <!-- اسم المتبرع -->
        <div class="col-12 col-md-6">
          <label class="form-label fs-8 fw-bold text-dark mb-1">{{
            t("donorName")
          }}</label>
          <input
            v-model="form.name"
            type="text"
            class="form-control fs-8 fw-medium"
            :disabled="!isEditing"
            required
          />
        </div>

        <!-- رقم الهاتف -->
        <div class="col-12 col-md-6">
          <label class="form-label fs-8 fw-bold text-dark mb-1">{{ t("phone") }}</label>
          <input
            v-model="form.phone"
            type="text"
            class="form-control fs-8 fw-medium"
            :disabled="!isEditing"
            placeholder="0590000000"
          />
        </div>

        <!-- البريد الإلكتروني -->
        <div class="col-12 col-md-6">
          <label class="form-label fs-8 fw-bold text-dark mb-1">{{ t("email") }}</label>
          <input
            v-model="form.email"
            type="email"
            class="form-control fs-8 fw-medium"
            :disabled="!isEditing"
            required
          />
        </div>

        <!-- فصيلة الدم -->
        <div class="col-12 col-md-6">
          <label class="form-label fs-8 fw-bold text-dark mb-1">{{
            t("bloodType")
          }}</label>
          <select
            v-model="form.blood_type_id"
            class="form-select fs-8 fw-medium"
            :disabled="!isEditing"
          >
            <option v-for="type in bloodTypes" :key="type.id" :value="type.id">
              {{ type.name }}
            </option>
          </select>
        </div>
      </div>

      <!-- أزرار الإلغاء والحفظ -->
      <div
        v-if="isEditing"
        class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top"
      >
        <button
          type="button"
          class="btn btn-light border px-4 py-1.5 rounded-3 fw-bold fs-8"
          @click="cancelEditing"
          :disabled="isSaving"
        >
          {{ t("cancel") }}
        </button>
        <button
          type="submit"
          class="btn btn-danger px-4 py-1.5 rounded-3 fw-bold fs-8 shadow-sm d-flex align-items-center gap-2"
          :disabled="isSaving"
        >
          <i class="bi" :class="isSaving ? 'bi-hourglass-split' : 'bi-check-lg'"></i>
          <span>{{ isSaving ? t("saving") : t("saveChanges") }}</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import apiClient from "@/api/axios";
import { useAuthStore } from "@/stores/authStore";

import defaultAvatarImg from "@/assets/images/pngtree-whatsapp-default-profile-photo-vector-png-image_17034397.webp";

const props = defineProps({
  profile: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(["update-profile"]);
const authStore = useAuthStore();
const currentLanguage = computed(() => localStorage.getItem("musaef_lang") || "ar");

const fileInput = ref(null);
const isEditing = ref(false);
const isSaving = ref(false);
const selectedFile = ref(null);
const previewUrl = ref(null);
const savedAvatarUrl = ref("");

const bloodTypes = [
  { id: 1, name: "A+" },
  { id: 2, name: "A-" },
  { id: 3, name: "B+" },
  { id: 4, name: "B-" },
  { id: 5, name: "AB+" },
  { id: 6, name: "AB-" },
  { id: 7, name: "O+" },
  { id: 8, name: "O-" },
];

const translations = {
  ar: {
    personalDataTitle: "البيانات الشخصية",
    editData: "تعديل البيانات",
    certifiedDonor: "متبرع معتمد في المنصة",
    donorName: "اسم المتبرع",
    phone: "رقم الهاتف",
    email: "البريد الإلكتروني",
    bloodType: "فصيلة الدم",
    cancel: "إلغاء",
    saving: "جاري الحفظ...",
    saveChanges: "حفظ التحديثات",
    successUpdate: "تم تحديث البيانات الشخصية بنجاح!",
    errorUpdate: "حدث خطأ أثناء تحديث البيانات.",
  },
  en: {
    personalDataTitle: "Personal Information",
    editData: "Edit Information",
    certifiedDonor: "Certified Donor on Platform",
    donorName: "Donor Name",
    phone: "Phone Number",
    email: "Email Address",
    bloodType: "Blood Type",
    cancel: "Cancel",
    saving: "Saving...",
    saveChanges: "Save Changes",
    successUpdate: "Personal information updated successfully!",
    errorUpdate: "An error occurred while updating profile data.",
  },
};

const t = (key) => {
  const lang = currentLanguage.value === "en" ? "en" : "ar";
  return translations[lang][key] || key;
};

const form = ref({
  name: "",
  phone: "",
  email: "",
  blood_type_id: 7,
});

const avatarPreview = computed(() => {
  if (previewUrl.value) return previewUrl.value;
  if (savedAvatarUrl.value) return savedAvatarUrl.value;

  const rawAvatar =
    authStore.user?.avatar_url ||
    authStore.userAvatar ||
    props.profile?.avatar_url ||
    props.profile?.user?.avatar_url ||
    props.profile?.user?.avatar;

  if (!rawAvatar || typeof rawAvatar !== "string") return defaultAvatarImg;

  let clean = rawAvatar.trim().replace(/\\/g, "/");
  if (!clean) return defaultAvatarImg;

  if (
    clean.startsWith("http://") ||
    clean.startsWith("https://") ||
    clean.startsWith("blob:") ||
    clean.startsWith("data:")
  ) {
    return clean;
  }

  clean = clean.replace(/^\/?storage\//, "").replace(/^\//, "");
  return `http://localhost:8000/storage/${clean}`;
});

watch(
  () => props.profile,
  (newVal) => {
    if (newVal && Object.keys(newVal).length > 0) {
      const userObj = newVal.user || {};
      form.value = {
        name: userObj.name || newVal.name || authStore.user?.name || "",
        phone: newVal.phone || userObj.phone || authStore.user?.phone || "",
        email: userObj.email || newVal.email || authStore.user?.email || "",
        blood_type_id: Number(
          newVal.blood_type_id ||
            userObj.blood_type_id ||
            authStore.user?.blood_type_id ||
            7
        ),
      };

      const initialAvatar =
        newVal.avatar_url || userObj.avatar_url || newVal.avatar || userObj.avatar;
      if (initialAvatar) {
        if (
          initialAvatar.startsWith("http") ||
          initialAvatar.startsWith("blob:") ||
          initialAvatar.startsWith("data:")
        ) {
          savedAvatarUrl.value = initialAvatar;
        } else {
          const clean = initialAvatar.replace(/^\/?storage\//, "").replace(/^\//, "");
          savedAvatarUrl.value = `http://localhost:8000/storage/${clean}`;
        }
      }
    }
  },
  { immediate: true, deep: true }
);

const startEditing = () => {
  isEditing.value = true;
};

const cancelEditing = () => {
  isEditing.value = false;
  selectedFile.value = null;
  previewUrl.value = null;
};

const triggerFileInput = () => {
  if (fileInput.value) fileInput.value.click();
};

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    selectedFile.value = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

const saveProfileData = async () => {
  isSaving.value = true;
  try {
    const formData = new FormData();
    formData.append("name", form.value.name);
    formData.append("email", form.value.email);
    if (form.value.phone) formData.append("phone", form.value.phone);
    if (form.value.blood_type_id)
      formData.append("blood_type_id", form.value.blood_type_id);

    if (selectedFile.value) {
      formData.append("avatar", selectedFile.value);
    }

    const res = await apiClient.post("/donor/profile/update", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    const responseData = res?.data?.data || res?.data;

    const userObj = responseData?.user || {};
    let updatedAvatarUrl =
      responseData?.avatar_url ||
      responseData?.avatar ||
      userObj.avatar_url ||
      userObj.avatar;

    if (updatedAvatarUrl && typeof updatedAvatarUrl === "string") {
      if (
        !updatedAvatarUrl.startsWith("http") &&
        !updatedAvatarUrl.startsWith("blob:") &&
        !updatedAvatarUrl.startsWith("data:")
      ) {
        const clean = updatedAvatarUrl.replace(/^\/?storage\//, "").replace(/^\//, "");
        updatedAvatarUrl = `http://localhost:8000/storage/${clean}`;
      }
      savedAvatarUrl.value = `${updatedAvatarUrl}?t=${new Date().getTime()}`;
    }

    const updatedUser = {
      ...(authStore.user || {}),
      ...userObj,
      name: form.value.name,
      email: form.value.email,
      avatar_url: savedAvatarUrl.value || authStore.user?.avatar_url,
      avatar: savedAvatarUrl.value || authStore.user?.avatar,
    };

    if (authStore.setUser) {
      authStore.setUser(updatedUser);
    } else if (authStore.updateUserData) {
      authStore.updateUserData(updatedUser);
    } else if (authStore.user) {
      Object.assign(authStore.user, updatedUser);
    }

    if (authStore.userAvatar !== undefined) {
      authStore.userAvatar = savedAvatarUrl.value;
    }

    const existingStorageUser = JSON.parse(
      localStorage.getItem("musaef_user") || localStorage.getItem("user") || "{}"
    );
    const mergedUser = { ...existingStorageUser, ...updatedUser };
    localStorage.setItem("musaef_user", JSON.stringify(mergedUser));
    localStorage.setItem("user", JSON.stringify(mergedUser));

    window.dispatchEvent(
      new CustomEvent("musaef_profile_updated", { detail: updatedUser })
    );

    isEditing.value = false;
    selectedFile.value = null;
    previewUrl.value = null;

    emit("update-profile", responseData);
    alert(t("successUpdate"));
  } catch (error) {
    console.error("خطأ أثناء حفظ التحديثات الشخصية:", error);
    alert(t("errorUpdate"));
  } finally {
    isSaving.value = false;
  }
};

const handleAvatarFallback = (e) => {
  if (e.target.src !== defaultAvatarImg) {
    e.target.src = defaultAvatarImg;
  }
};
</script>

<style scoped>
.font-arial {
  font-family: Arial, Helvetica, sans-serif !important;
}

.dir-rtl {
  direction: rtl;
}
.dir-ltr {
  direction: ltr;
}

.donor-profile-avatar {
  width: 80px;
  height: 80px;
  object-fit: cover;
}

.avatar-upload-btn {
  width: 28px;
  height: 28px;
}

.fs-6 {
  font-size: 1.05rem;
}
.fs-8 {
  font-size: 0.82rem;
}
</style>
