package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet.PayPal;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface PaypalRepository extends JpaRepository<PayPal, Long> {
}
