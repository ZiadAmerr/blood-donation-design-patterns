package com.blooddonation.system.models.donations.BloodDonation;
import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.Beneficiary;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class BloodStock implements IBloodStock {
    private List<Beneficiary> beneficiaries = new ArrayList<>();
    private static BloodStock instance;
    private Map<BloodTypeEnum, Integer> bloodAmount = new HashMap<>();

    private BloodStock() {}

    public static BloodStock getInstance() {
        if (instance == null) {
            instance = new BloodStock();
        }
        return instance;
    }

    public Map<BloodTypeEnum, Integer> getBloodAmount() {
        return bloodAmount;
    }

    @Override
    public void registerBeneficiary(Beneficiary beneficiary) {
        beneficiaries.add(beneficiary);
    }

    @Override
    public void removeBeneficiary(Beneficiary beneficiary) {
        beneficiaries.remove(beneficiary);
    }

    @Override
    public void notifyBeneficiaries() {
        for (Beneficiary beneficiary : beneficiaries) {
            beneficiary.update();
        }
    }
}

