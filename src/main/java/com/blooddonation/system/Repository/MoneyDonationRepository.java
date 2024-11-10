package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.MoneyDonation.MoneyDonation;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface MoneyDonationRepository extends JpaRepository<MoneyDonation, Long> {
}