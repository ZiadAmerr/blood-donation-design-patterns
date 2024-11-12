package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.BloodDonation.BloodDonation;
import org.springframework.data.jpa.repository.JpaRepository;

public interface BloodDonationRepository extends JpaRepository<BloodDonation, Long> {
}
