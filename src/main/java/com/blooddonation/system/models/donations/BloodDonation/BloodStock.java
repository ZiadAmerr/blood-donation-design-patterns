package com.blooddonation.system.models.donations.BloodDonation;
import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.Beneficiary;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class BloodStock implements IBloodStock {
    private List<Beneficiary> beneficiaries;
    private static BloodStock instance;
    private Map<BloodTypeEnum, Integer> bloodAmount;

    private BloodStock() {
        beneficiaries = new ArrayList<>();
        bloodAmount = new HashMap<>();
    }

    public static BloodStock getInstance() {
        if (instance == null) {
            instance = new BloodStock();
        }
        return instance;
    }

    public Map<BloodTypeEnum, Integer> getBloodAmount() {
        return bloodAmount;
    }

    public void increaseBloodAmount(BloodTypeEnum bloodType, int liters) {

        int currentAmount = bloodAmount.getOrDefault(bloodType, 0);
        bloodAmount.put(bloodType, currentAmount + liters);
        notifyBeneficiaries(bloodType, currentAmount + liters);
    }

    // Method to decrease the blood amount for a specific blood type
    public void decreaseBloodAmount(BloodTypeEnum bloodType, int liters) {
        if (liters < 0)
        {
            throw new IllegalArgumentException("Amount to decrease must be positive.");
        }

        int currentAmount = bloodAmount.getOrDefault(bloodType, 0);

        if (currentAmount < liters)
        {
            throw new IllegalArgumentException("Insufficient blood amount for the given type.");
        }
        bloodAmount.put(bloodType, currentAmount - liters);
        notifyBeneficiaries(bloodType, currentAmount - liters);
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
    public void notifyBeneficiaries(BloodTypeEnum bloodType, int newAmount) {
        for (Beneficiary beneficiary : beneficiaries) {
            beneficiary.update(bloodType, newAmount);
        }
    }
}

