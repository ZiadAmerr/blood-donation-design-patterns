package com.blooddonation.system.models.donations.BloodDonation.Beneficiaries;

import com.blooddonation.system.models.donations.BloodDonation.BloodStock;
import com.blooddonation.system.models.donations.BloodDonation.BloodTypeEnum;
import com.blooddonation.system.models.donations.BloodDonation.IBloodStock;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class BloodBank implements Beneficiary {

    private IBloodStock bloodStock;
    private BloodStock bloodBank = BloodStock.getInstance();
    private Map<BloodTypeEnum, Integer> bloodStorage;

    public BloodBank(IBloodStock bloodStock)
    {
        this.bloodStock = bloodStock;
        bloodStorage = new HashMap<>();
        bloodStock.registerBeneficiary(this);
    }

    @Override
    public void update(BloodTypeEnum bloodType, int newAmount)
    {
        int currentAmount = bloodStorage.getOrDefault(bloodType, 0);
        bloodStorage.put(bloodType, newAmount);
    }
    @Override
    public boolean receiveBloodDonation(BloodTypeEnum bloodType, int liters) {
        if (liters <= 0) {
            throw new IllegalArgumentException("Liters to receive must be positive.");
        }
        try
        {
            bloodBank.decreaseBloodAmount(bloodType, liters);
            return true;
        }
        catch (IllegalArgumentException e)
        {
            // Handle the case where there's not enough blood in the stock
            System.out.println("Error: " + e.getMessage());
            return false;
        }
    }
}