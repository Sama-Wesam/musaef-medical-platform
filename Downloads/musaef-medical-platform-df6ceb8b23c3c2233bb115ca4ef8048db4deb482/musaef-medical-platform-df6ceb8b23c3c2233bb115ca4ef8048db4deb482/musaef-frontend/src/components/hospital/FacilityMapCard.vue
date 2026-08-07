<template>
  <section class="map-card">
    <img
      src="/images/hospital-map.png"
      alt="خريطة موقع الجهة الطبية"
      class="map-image"
    />

    <div class="map-overlay">
      <span class="hospital-pin pin-one">
        <i class="bi bi-hospital-fill"></i>
      </span>

      <span class="hospital-pin pin-two">
        <i class="bi bi-hospital-fill"></i>
      </span>

      <span class="hospital-pin pin-three">
        <i class="bi bi-hospital-fill"></i>
      </span>

      <span class="hospital-pin pin-four">
        <i class="bi bi-hospital-fill"></i>
      </span>
    </div>
  </section>
</template>

<style scoped>
.map-card {
  width: 100%;
  min-height: 780px;
  position: relative;
  overflow: hidden;
  border: 1px solid #eceef2;
  border-radius: 12px;
  background-color: #e5e7eb;
}

.map-image {
  width: 100%;
  height: 780px;
  display: block;
  object-fit: cover;
}

.map-overlay {
  position: absolute;
  inset: 0;
  z-index: 2;
  pointer-events: none;
}

.hospital-pin {
  width: 35px;
  height: 35px;
  position: absolute;
  display: grid;
  place-items: center;
  border: 3px solid #ffffff;
  border-radius: 50% 50% 50% 0;
  transform: rotate(-45deg);
  background-color: #ef4444;
  color: #ffffff;
  box-shadow: 0 3px 8px rgba(15, 23, 42, 0.2);
}

.hospital-pin i {
  transform: rotate(45deg);
  font-size: 15px;
}

.pin-one {
  top: 26%;
  right: 23%;
}

.pin-two {
  top: 62%;
  right: 40%;
}

.pin-three {
  top: 70%;
  left: 8%;
}

.pin-four {
  top: 58%;
  left: 26%;
}

@media (max-width: 700px) {
  .map-card {
    min-height: 300px;
  }

  .map-image {
    height: 300px;
  }
}
</style>