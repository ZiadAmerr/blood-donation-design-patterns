package com.sdp.project.models.bloodbank;

public interface IBloodStock {
    void addBeneficiary(IBeneficiary beneficiary);
    void removeBeneficiary(IBeneficiary beneficiary);
    void notifyBeneficiaries();
}
