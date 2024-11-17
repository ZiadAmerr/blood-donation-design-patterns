package com.sdp.project.models.bloodbank;

import java.util.*;

public class BloodStock {

    // Singleton instance
    private static BloodStock instance;

    // Observers implementing the IBeneficiary interface
    private final List<IBeneficiary> beneficiaries = new ArrayList<>();

    // Blood stock data
    private final Map<BloodType, Integer> bloodAmount = new HashMap<>();

    // Private constructor for Singleton
    private BloodStock() {
        // Initialize blood stock levels if needed
        for (BloodType type : BloodType.values()) {
            bloodAmount.put(type, 0);
        }
    }

    // Public method to get the Singleton instance
    public static BloodStock getInstance() {
        if (instance == null) {
            instance = new BloodStock();
        }
        return instance;
    }

    // Add a beneficiary (Observer pattern)
    public void addBeneficiary(IBeneficiary beneficiary) {
        if (!beneficiaries.contains(beneficiary)) {
            beneficiaries.add(beneficiary);
        }
    }

    // Remove a beneficiary (Observer pattern)
    public void removeBeneficiary(IBeneficiary beneficiary) {
        beneficiaries.remove(beneficiary);
    }

    // Notify all beneficiaries of a blood stock update
    public void notifyBeneficiaries() {
        for (IBeneficiary beneficiary : beneficiaries) {
            beneficiary.update(this);
        }
    }

    // Add blood to the stock
    public void addBlood(BloodType type, int amount) {
        bloodAmount.put(type, bloodAmount.getOrDefault(type, 0) + amount);
        notifyBeneficiaries(); // Notify beneficiaries after stock update
    }

    // Withdraw blood from the stock
    public boolean withdrawBlood(BloodType type, int amount) {
        if (bloodAmount.getOrDefault(type, 0) >= amount) {
            bloodAmount.put(type, bloodAmount.get(type) - amount);
            notifyBeneficiaries(); // Notify beneficiaries after stock update
            return true;
        }
        return false;
    }

    // Get the current blood stock levels
    public Map<BloodType, Integer> getBloodAmount() {
        return Collections.unmodifiableMap(bloodAmount);
    }

    // Set blood stock levels (useful for initial data loading)
    public void setBloodAmount(BloodType type, int amount) {
        bloodAmount.put(type, amount);
        notifyBeneficiaries(); // Notify beneficiaries after stock update
    }

    // Get the list of beneficiaries (useful for display or debugging)
    public List<IBeneficiary> getBeneficiaries() {
        return Collections.unmodifiableList(beneficiaries);
    }
}
