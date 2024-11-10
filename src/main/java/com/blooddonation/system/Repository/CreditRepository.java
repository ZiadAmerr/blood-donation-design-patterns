package com.blooddonation.system.Repository;

import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment.Credit;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface CreditRepository extends JpaRepository<Credit, Long> {
}