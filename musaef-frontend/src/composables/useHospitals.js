import { computed, ref, onUnmounted } from 'vue';
import { useHospitalStore } from '@/stores/hospitalStore';

export function useHospitals() {
  const hospitalStore = useHospitalStore();
  const pollingTimer = ref(null);

  const hospitalProfile = computed(() => hospitalStore.profile);
  const inventory = computed(() => hospitalStore.inventory);
  const loading = computed(() => hospitalStore.loading);

  const fetchInventory = async () => {
    await hospitalStore.fetchInventory();
  };

  const addUnits = async (bloodType, count) => {
    await hospitalStore.updateStock(bloodType, Number(count));
  };

  const withdrawUnits = async (bloodType, count) => {
    const item = hospitalStore.inventory.find(i => i.type === bloodType || i.blood_type === bloodType);
    if (item && (item.available >= count || item.units >= count)) {
      await hospitalStore.updateStock(bloodType, -Number(count));
      return true;
    }
    return false;
  };

  const startInventoryPolling = (intervalMs = 15000) => {
    stopInventoryPolling();
    fetchInventory();
    pollingTimer.value = setInterval(() => {
      fetchInventory();
    }, intervalMs);
  };

  const stopInventoryPolling = () => {
    if (pollingTimer.value) {
      clearInterval(pollingTimer.value);
      pollingTimer.value = null;
    }
  };

  onUnmounted(() => {
    stopInventoryPolling();
  });

  return {
    hospitalProfile,
    inventory,
    loading,
    fetchInventory,
    addUnits,
    withdrawUnits,
    startInventoryPolling,
    stopInventoryPolling
  };
}
