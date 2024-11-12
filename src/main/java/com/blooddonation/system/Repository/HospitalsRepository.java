package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.Hospitals;
import org.springframework.data.jpa.repository.JpaRepository;

public interface HospitalsRepository extends JpaRepository<Hospitals, Long> {
}
