<template>
  <header class="dashboard-header" dir="rtl">
    <h2>الإحصائيات العامة</h2>
  </header>
</template>

<style scoped>
.dashboard-header {
  width: 100%;
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  justify-content: flex-start;
}

.dashboard-header h2 {
  margin: 0;
  color: #111111;
  font-size: 16px;
  font-weight: 800;
  line-height: 1.4;
  text-align: right;
}

/* التابلت */
@media (max-width: 900px) {
  .dashboard-header {
    margin-bottom: 15px;
  }

  .dashboard-header h2 {
    font-size: 15px;
  }
}

/* الهاتف */
@media (max-width: 600px) {
  .dashboard-header {
    margin-bottom: 12px;
  }

  .dashboard-header h2 {
    font-size: 14px;
  }
}
</style>