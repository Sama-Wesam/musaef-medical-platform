import { computed, ref, onUnmounted } from 'vue';
import { useEmergencyStore } from '@/stores/emergencyRadarStore';

export function useEmergency() {
  const emergencyStore = useEmergencyStore();
  const pollingTimer = ref(null);

  const requests = computed(() => emergencyStore.emergencyRequests);
  const activeRequests = computed(() => emergencyStore.activeRequests);
  const criticalRequests = computed(() => emergencyStore.criticalRequests);
  const loading = computed(() => emergencyStore.loading);

  const fetchActiveEmergencies = async (params = {}) => {
    await emergencyStore.fetchActiveEmergencies(params);
  };

  const createEmergencyRequest = async (newRequest) => {
    await emergencyStore.addRequest(newRequest);
  };

  const respondToEmergency = async (id, donorInfo = {}) => {
    await emergencyStore.respondToRequest(id, donorInfo);
  };

  // تفعيل آلية الاستطلاع التلقائي المباشر كل N ثانية
  const startPolling = (intervalMs = 8000, params = {}) => {
    stopPolling();
    fetchActiveEmergencies(params);
    pollingTimer.value = setInterval(() => {
      fetchActiveEmergencies(params);
    }, intervalMs);
  };

  const stopPolling = () => {
    if (pollingTimer.value) {
      clearInterval(pollingTimer.value);
      pollingTimer.value = null;
    }
  };

  onUnmounted(() => {
    stopPolling();
  });

  return {
    requests,
    activeRequests,
    criticalRequests,
    loading,
    fetchActiveEmergencies,
    createEmergencyRequest,
    respondToEmergency,
    startPolling,
    stopPolling
  };
}
