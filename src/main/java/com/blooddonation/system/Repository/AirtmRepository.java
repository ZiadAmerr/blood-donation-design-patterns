package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet.AirTM;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface AirtmRepository extends JpaRepository<AirTM, Long> {
}