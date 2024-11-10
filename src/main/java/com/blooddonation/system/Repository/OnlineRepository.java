package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.Online;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface OnlineRepository extends JpaRepository<Online, Long> {
}