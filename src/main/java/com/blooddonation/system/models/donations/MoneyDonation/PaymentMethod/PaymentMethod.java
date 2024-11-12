package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod;

public interface PaymentMethod {
    boolean processPayment(float amount);
}
