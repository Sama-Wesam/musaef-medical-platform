<template>
  <div class="row">
    <!-- كرت النقاط والمكافآت -->
    <div class="col-md-6 mb-4">
      <div class="card h-100 shadow-sm border-0">
        <div class="card-body p-4">
          <h5 class="card-title fw-bold text-dark mb-4">النقاط والمكافآت</h5>
          <div class="row align-items-center">
            <div class="col-md-5 text-center">
              <i class="bi bi-award-fill text-warning display-1 mb-2"></i>
            </div>
            <div class="col-md-7 text-center">
              <h1 class="display-4 fw-bold text-danger mb-0">350</h1>
              <p class="text-dark small mb-0">نقطة مجانية</p>
              <p class="text-muted small mt-2 mb-2"> <span class="fw-bold">150</span> نقطة باقية على المستوى التالي</p>
              <div class="progress rounded-pill mb-3" style="height: 10px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <button class="btn btn-outline-danger btn-sm rounded-pill px-4">استبدل نقاطك</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- كرت الأثر الإنساني -->
    <div class="col-md-6 mb-4">
      <div class="card h-100 shadow-sm border-0">
        <div class="card-body p-4">
          <h5 class="card-title fw-bold text-dark mb-4 text-end">الأثر الإنساني</h5>
          <div class="row align-items-center">
            <div class="col text-center border-end">
              <i class="bi bi-heart-fill text-danger fs-1 mb-2"></i>
              <h1 class="display-5 fw-bold text-danger mb-0">12</h1>
              <p class="text-dark mb-0">حالة تدريبية</p>
            </div>
            <div class="col text-center">
              <i class="bi bi-droplet-half text-danger fs-1 mb-2"></i>
              <h1 class="display-5 fw-bold text-danger mb-0">8</h1>
              <p class="text-dark mb-0">وحدات دم تبرع بها</p>
            </div>
          </div>
          <p class="text-muted small text-center mt-4">نشارككم لتستمر الحياة، في أمان الله</p>
        </div>
      </div>
    </div>
  </div>
</template>