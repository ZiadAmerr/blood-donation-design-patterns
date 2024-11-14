package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.Hospital;
import org.springframework.data.jpa.repository.JpaRepository;

public interface HospitalRepository extends JpaRepository<Hospital, Long> {
}
