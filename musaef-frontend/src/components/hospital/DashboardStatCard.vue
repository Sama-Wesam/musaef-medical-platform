<template>
  <article class="stat-card">
    <!-- الجزء العلوي -->
    <div class="stat-top">
      <!-- المعلومات -->
      <div class="stat-content">
        <h3 :style="{ color: titleColor }">
          {{ title }}
        </h3>

        <strong
          class="stat-value"
          :style="{ color: progressColor }"
        >
          {{ value }}
        </strong>

        <span class="stat-label">
          {{ label }}
        </span>
      </div>

      <!-- الأيقونة -->
      <div
        class="stat-icon"
        :style="{
          color: iconColor,
          backgroundColor: iconBackground,
        }"
      >
        <i :class="icon"></i>
      </div>
    </div>

    <!-- شريط التقدم -->
    <div class="progress-track">
      <div
        class="progress-fill"
        :style="{
          width: progressWidth,
          backgroundColor: progressColor,
        }"
      ></div>
    </div>

    <!-- الجزء السفلي -->
    <div class="stat-footer">
      <span class="footer-text">
        {{ footerText }}
      </span>

      <span
        class="footer-badge"
        :style="{
          color: footerColor,
          backgroundColor: footerBackground,
        }"
      >
        {{ footer }}
      </span>
    </div>
  </article>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },

  value: {
    type: [String, Number],
    required: true,
  },

  label: {
    type: String,
    default: '',
  },

  icon: {
    type: String,
    required: true,
  },

  iconColor: {
    type: String,
    default: '#2563eb',
  },

  iconBackground: {
    type: String,
    default: '#eef3ff',
  },

  progressColor: {
    type: String,
    default: '#2563eb',
  },

  progressWidth: {
    type: String,
    default: '60%',
  },

  footer: {
    type: String,
    default: '',
  },

  footerText: {
    type: String,
    default: '',
  },

  footerColor: {
    type: String,
    default: '#2563eb',
  },

  footerBackground: {
    type: String,
    default: '#eef3ff',
  },

  titleColor: {
    type: String,
    default: '#111827',
  },
})
</script>

<style scoped>
* {
  box-sizing: border-box;
}

.stat-card {
  width: 100%;
  min-width: 0;
  min-height: 118px;
  padding: 12px 10px 10px;

  display: flex;
  flex-direction: column;

  direction: rtl;
  overflow: hidden;

  border: 1px solid #f1f2f4;
  border-radius: 10px;

  background-color: #ffffff;
  box-shadow: 0 3px 10px rgba(15, 23, 42, 0.025);
}

/* =========================
   الجزء العلوي
========================= */

.stat-top {
  width: 100%;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 10px;
}

.stat-content {
  min-width: 0;
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  text-align: right;
}

.stat-content h3 {
  width: 100%;
  min-height: 18px;
  margin: 0 0 5px;

  color: #111827;
  font-size: 10px;
  font-weight: 700;
  line-height: 1.5;

  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.stat-value {
  width: 100%;
  margin-bottom: 4px;

  font-size: 18px;
  font-weight: 500;
  line-height: 1.2;
}

.stat-label {
  width: 100%;
  color: #9ca3af;
  font-size: 8px;
  line-height: 1.4;
}

/* =========================
   الأيقونة
========================= */

.stat-icon {
  width: 50px;
  height: 50px;
  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  font-size: 20px;
}

.stat-icon i {
  line-height: 1;
}

/* =========================
   شريط التقدم
========================= */

.progress-track {
  width: 100%;
  height: 3px;
  margin-top: 11px;

  overflow: hidden;

  border-radius: 20px;
  background-color: #dedede;

  direction: ltr;
}

.progress-fill {
  height: 100%;
  border-radius: 20px;
}

/* =========================
   الجزء السفلي
========================= */

.stat-footer {
  width: 100%;
  min-height: 25px;
  margin-top: 6px;

  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 8px;

  direction: rtl;
}

.footer-text {
  color: #8b8b8b;
  font-size: 8px;
  white-space: nowrap;
}

.footer-badge {
  min-width: 35px;
  height: 20px;
  padding: 0 9px;

  display: inline-flex;
  align-items: center;
  justify-content: center;

  border-radius: 12px;

  font-size: 10px;
  font-weight: 600;
  line-height: 1;
}

/* =========================
   الشاشات المتوسطة
========================= */

@media (max-width: 1100px) {
  .stat-card {
    min-height: 125px;
  }

  .stat-content h3 {
    font-size: 11px;
  }

  .stat-label {
    font-size: 9px;
  }
}

/* =========================
   الهاتف
========================= */

@media (max-width: 650px) {
  .stat-card {
    min-height: 120px;
    padding: 13px;
  }

  .stat-icon {
    width: 46px;
    height: 46px;
    font-size: 19px;
  }

  .stat-value {
    font-size: 19px;
  }
}
</style>