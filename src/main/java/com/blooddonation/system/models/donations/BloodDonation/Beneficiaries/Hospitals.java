package com.blooddonation.system.models.donations.BloodDonation.Beneficiaries;

import com.blooddonation.system.models.donations.BloodDonation.BloodStock;
import com.blooddonation.system.models.donations.BloodDonation.BloodTypeEnum;
import com.blooddonation.system.models.donations.BloodDonation.IBloodStock;

public class Hospitals implements Beneficiary {
    private IBloodStock bloodStock;
    private BloodStock bloodBank = BloodStock.getInstance();

    public Hospitals(IBloodStock bloodStock)
    {
        this.bloodStock = bloodStock;
        bloodStock.registerBeneficiary(this);
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
