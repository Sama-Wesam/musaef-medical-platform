import { computed } from 'vue';
import { useDonorStore } from '@/stores/donorStore';
import donorApi from '@/api/donor';

export function useDonors() {
  const donorStore = useDonorStore();

  const donors = computed(() => donorStore.donors);
  const donorCard = computed(() => donorStore.myCard);
  const donationsHistory = computed(() => donorStore.donationsHistory);
  const loading = computed(() => donorStore.loading);

  const getDonorsList = () => donorStore.donors;

  const fetchRewardsAndCard = async () => {
    try {
      const res = await donorApi.getRewardsAndCard();
      if (res?.data) {
        donorStore.myCard = res.data;
      }
    } catch (e) {
      console.warn('تعذر جلب الشارات والمكافآت من قاعدة البيانات:', e);
    }
  };

  const fetchDonationHistory = async () => {
    try {
      const res = await donorApi.getDonationHistory();
      if (res?.data) {
        donorStore.donationsHistory = res.data;
      }
    } catch (e) {
      console.warn('تعذر جلب سجل التبرعات من قاعدة البيانات:', e);
    }
  };

  const recordDonation = async (donationData = null) => {
    try {
      await donorApi.acceptDonationRequest(donationData?.requestId);
      await fetchDonationHistory();
      await fetchRewardsAndCard();
    } catch (e) {
      console.error('خطأ في تسجيل عملية التبرع:', e);
    }
  };

  return {
    donors,
    donorCard,
    donationsHistory,
    loading,
    getDonorsList,
    fetchRewardsAndCard,
    fetchDonationHistory,
    recordDonation
  };
}
