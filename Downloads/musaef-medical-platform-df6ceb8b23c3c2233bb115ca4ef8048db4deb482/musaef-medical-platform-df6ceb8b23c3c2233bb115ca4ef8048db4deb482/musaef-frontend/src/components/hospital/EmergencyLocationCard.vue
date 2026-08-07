<template>
  <section class="location-card">
    <h3>الموقع على الخريطة</h3>

    <div class="fake-map">
      <div class="road road-one"></div>
      <div class="road road-two"></div>
      <div class="road road-three"></div>

      <span class="route-number">
        40
      </span>

      <div class="hospital-pin">
        <i class="bi bi-hospital-fill"></i>
      </div>
    </div>

    <div class="map-actions">
      <button
        type="button"
        class="primary-map-button"
      >
        <i class="bi bi-check-lg"></i>
        قبول الطلب
      </button>

      <button
        type="button"
        class="secondary-map-button"
      >
        رفض الطلب
      </button>
    </div>
  </section>
</template>

<style scoped>
.location-card {
  padding: 14px;
  border: 1px solid #eceef2;
  border-radius: 10px;
  background-color: #ffffff;
}

.location-card h3 {
  margin: 0 0 12px;
  font-size: 10px;
  font-weight: 800;
}

.fake-map {
  height: 112px;
  position: relative;
  overflow: hidden;
  border-radius: 9px;
  background:
    linear-gradient(135deg, #e9f2e5 25%, transparent 25%) 0 0 / 44px 44px,
    linear-gradient(45deg, #f7efd7 25%, #eef3e8 25%) 0 0 / 54px 54px;
}

.road {
  height: 6px;
  position: absolute;
  border: 2px solid #ffffff;
  border-radius: 20px;
  background-color: #f4d078;
}

.road-one {
  width: 150%;
  top: 48px;
  right: -30px;
  transform: rotate(-18deg);
}

.road-two {
  width: 130%;
  top: 76px;
  right: -10px;
  transform: rotate(17deg);
}

.road-three {
  width: 100%;
  top: 24px;
  right: 12px;
  transform: rotate(7deg);
}

.route-number {
  position: absolute;
  top: 20px;
  right: 42px;
  padding: 2px 4px;
  border-radius: 2px;
  background-color: #65a30d;
  color: #ffffff;
  font-size: 13px;
}

.hospital-pin {
  width: 31px;
  height: 31px;
  position: absolute;
  top: 50%;
  left: 50%;
  display: grid;
  place-items: center;
  transform: translate(-50%, -50%);
  border-radius: 50% 50% 50% 0;
  background-color: #ef4444;
  color: #ffffff;
  font-size: 15px;
  rotate: -45deg;
}

.hospital-pin i {
  rotate: 45deg;
}

.map-actions {
  margin-top: 10px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
}

.map-actions button {
  height: 29px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
}

.primary-map-button {
  border: 0;
  background-color: #dc2626;
  color: #ffffff;
}

.secondary-map-button {
  border: 1px solid #ef4444;
  background-color: #ffffff;
  color: #dc2626;
}
</style>