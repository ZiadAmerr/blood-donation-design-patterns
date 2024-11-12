package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.WaitingPatients;
import org.springframework.data.jpa.repository.JpaRepository;

public interface WaitingPatientsRepository extends JpaRepository<WaitingPatients, Long> {
}
