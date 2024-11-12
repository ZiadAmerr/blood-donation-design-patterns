package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.BloodBank;
import org.springframework.data.jpa.repository.JpaRepository;

public interface BloodBankRepository extends JpaRepository<BloodBank, Long> {
}
