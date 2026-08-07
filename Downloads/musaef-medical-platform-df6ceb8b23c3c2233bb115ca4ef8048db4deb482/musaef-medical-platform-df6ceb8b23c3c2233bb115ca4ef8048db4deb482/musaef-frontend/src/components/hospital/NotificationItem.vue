<template>
  <article
    class="notification-item"
    :class="{
      urgent: notification.isUrgent,
      unread: !notification.isRead,
    }"
    @click="handleRead"
  >
    <div
      class="notification-icon"
      :style="{
        color: notification.color,
        backgroundColor: notification.background,
      }"
    >
      <i :class="notification.icon"></i>
    </div>

    <div class="notification-content">
      <div class="notification-title">
        <h3>{{ notification.title }}</h3>

        <span
          v-if="!notification.isRead"
          class="unread-dot"
          aria-label="إشعار غير مقروء"
        ></span>
      </div>

      <p>{{ notification.description }}</p>
    </div>

    <time class="notification-time">
      {{ notification.time }}
    </time>

    <button
      type="button"
      class="more-button"
      aria-label="خيارات الإشعار"
      @click.stop
    >
      <i class="bi bi-three-dots-vertical"></i>
    </button>
  </article>
</template>

<script setup>
const props = defineProps({
  notification: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['read'])

const handleRead = () => {
  if (!props.notification.isRead) {
    emit('read', props.notification.id)
  }
}
</script>

<style scoped>
.notification-item {
  width: 100%;
  min-height: 76px;
  padding: 14px 16px;

  display: grid;
  grid-template-columns:
    46px
    minmax(0, 1fr)
    90px
    24px;

  align-items: center;
  gap: 14px;

  direction: rtl;

  border: 1px solid #e5e7eb;
  border-radius: 8px;

  background-color: #ffffff;
  cursor: pointer;

  transition:
    border-color 0.2s ease,
    background-color 0.2s ease;
}

.notification-item.urgent {
  border-color: #fecaca;
  background-color: #fffafa;
}

.notification-item.unread {
  border-color: #fca5a5;
}

.notification-item:hover {
  border-color: #ef4444;
}

.notification-icon {
  width: 42px;
  height: 42px;

  display: grid;
  place-items: center;

  border-radius: 50%;
  font-size: 27px;
}

.notification-content {
  min-width: 0;
}

.notification-title {
  display: flex;
  align-items: flex-start;
  gap: 8px;
}

.notification-title h3 {
  margin: 0 0 6px;

  color: #111827;
  font-size: 18px;
  font-weight: 800;
  line-height: 1.6;
}

.unread-dot {
  width: 7px;
  height: 7px;
  margin-top: 6px;
  flex-shrink: 0;

  border-radius: 50%;
  background-color: #dc2626;
}

.notification-content p {
  margin: 0;

  color: #9ca3af;
  font-size: 17px;
  line-height: 1.7;
}

.notification-time {
  color: #6b7280;
  text-align: center;
  white-space: nowrap;
  font-size: 12px;
}

.more-button {
  width: 24px;
  height: 34px;
  padding: 0;

  display: grid;
  place-items: center;

  border: 0;
  background-color: transparent;
  color: #64748b;

  font-size: 17px;
  cursor: pointer;
}

.more-button:hover {
  color: #dc2626;
}

@media (max-width: 700px) {
  .notification-item {
    grid-template-columns:
      42px
      minmax(0, 1fr)
      22px;

    gap: 10px;
    padding: 13px;
  }

  .notification-time {
    grid-column: 2;
    text-align: right;
  }

  .more-button {
    grid-column: 3;
    grid-row: 1;
  }
}
</style>