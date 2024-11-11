package com.blooddonation.system.models.donations.BloodDonation.Beneficiaries;

import com.blooddonation.system.models.donations.BloodDonation.BloodStock;
import com.blooddonation.system.models.donations.BloodDonation.IBloodStock;

public class BloodBank implements Beneficiary {

    private IBloodStock bloodStock;

    public BloodBank(IBloodStock bloodStock)
    {
        this.bloodStock = bloodStock;
        bloodStock.registerBeneficiary(this);
    }

    @Override
    public boolean update() {
        // Implementation for updating BloodBank status
        return true;
    }
}