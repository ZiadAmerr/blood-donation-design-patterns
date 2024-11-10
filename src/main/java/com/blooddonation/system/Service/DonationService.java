package com.blooddonation.system.Service;

import com.blooddonation.system.models.donations.Donation;
import java.util.List;
import java.util.Optional;

public interface DonationService {
    Donation saveDonation(Donation donation);
    Optional<Donation> getDonationById(Long id);
    List<Donation> getAllDonations();
    void deleteDonation(Long id);
}