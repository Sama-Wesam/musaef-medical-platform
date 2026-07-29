import { computed } from 'vue';
import { useDonorStore } from '@/stores/donorStore';

export function useDonors() {
  const donorStore = useDonorStore();

  const donors = computed(() => donorStore.donors);
  const donorCard = computed(() => donorStore.myCard);
  const donationsHistory = computed(() => donorStore.donationsHistory);
  const loading = computed(() => donorStore.loading);

  const getDonorsList = () => donorStore.donors;

  const fetchRewardsAndCard = async () => {
    await donorStore.fetchRewardsAndCard();
  };

  const fetchDonationHistory = async () => {
    await donorStore.fetchDonationHistory();
  };

  const addPoints = (amount) => {
    donorStore.addPoints(amount);
  };

  const recordDonation = () => {
    donorStore.recordDonation();
  };

  return {
    donors,
    donorCard,
    donationsHistory,
    loading,
    getDonorsList,
    fetchRewardsAndCard,
    fetchDonationHistory,
    addPoints,
    recordDonation
  };
}
