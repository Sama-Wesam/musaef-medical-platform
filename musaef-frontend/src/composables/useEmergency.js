import { computed } from 'vue';
import { useEmergencyStore } from '@/stores/emergencyRadarStore';

export function useEmergency() {
  const emergencyStore = useEmergencyStore();

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

  return {
    requests,
    activeRequests,
    criticalRequests,
    loading,
    fetchActiveEmergencies,
    createEmergencyRequest,
    respondToEmergency
  };
}
