package com.sdp.project.models.bloodbank;

import java.util.ArrayList;
import java.util.List;


public class BloodStock {
    private List<String> beneficiaries;

    public BloodStock() {
        this.beneficiaries = new ArrayList<>();
    }

    public void addBeneficiary(String beneficiary) {
        beneficiaries.add(beneficiary);
    }

    public void removeBeneficiary(String beneficiary) {
        beneficiaries.remove(beneficiary);
    }

    public List<String> getBeneficiaries() {
        return beneficiaries;
    }
}
