import { computed } from 'vue';
import { useHospitalStore } from '@/stores/hospitalStore';

export function useHospitals() {
  const hospitalStore = useHospitalStore();

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
    const item = hospitalStore.inventory.find(i => i.type === bloodType);
    if (item && item.available >= count) {
      await hospitalStore.updateStock(bloodType, -Number(count));
      return true;
    }
    return false;
  };

  return {
    hospitalProfile,
    inventory,
    loading,
    fetchInventory,
    addUnits,
    withdrawUnits
  };
}
