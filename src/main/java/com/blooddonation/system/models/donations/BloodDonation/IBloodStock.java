package com.blooddonation.system.models.donations.BloodDonation;

public interface IBloodStock {
    void addBeneficiary(Beneficiary beneficiary);
    void removeBeneficiary(Beneficiary beneficiary);
    void notifyBeneficiaries();
}
