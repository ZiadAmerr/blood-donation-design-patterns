package com.blooddonation.system.models.donations.BloodDonation;
import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.Beneficiary;

public interface IBloodStock {
    void registerBeneficiary(Beneficiary beneficiary);
    void removeBeneficiary(Beneficiary beneficiary);
    void notifyBeneficiaries();
}
