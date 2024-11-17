package com.sdp.project.models.blood;

public interface IBloodStock {
    void addBeneficiary(IBeneficiary beneficiary);
    void removeBeneficiary(IBeneficiary beneficiary);
    void notifyBeneficiaries();
}
