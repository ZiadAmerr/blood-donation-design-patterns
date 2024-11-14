package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.WaitingPatient;
import org.springframework.data.jpa.repository.JpaRepository;

public interface WaitingPatientRepository extends JpaRepository<WaitingPatient, Long> {
}
