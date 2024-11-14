package com.blooddonation.system.models.donations.BloodDonation;
import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.Beneficiary;
import jakarta.persistence.Embeddable;

public interface IBloodStock {
    void registerBeneficiary(Beneficiary beneficiary);
    void removeBeneficiary(Beneficiary beneficiary);
    void notifyBeneficiaries(BloodTypeEnum bloodType, int newAmount);
}
