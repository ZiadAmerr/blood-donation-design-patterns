package com.blooddonation.system.models.donations.BloodDonation.Beneficiaries;

import com.blooddonation.system.models.donations.BloodDonation.IBloodStock;

public class Hospitals implements Beneficiary {
    private IBloodStock bloodStock;

    public Hospitals(IBloodStock bloodStock)
    {
        this.bloodStock = bloodStock;
        bloodStock.registerBeneficiary(this);
    }
    @Override
    public boolean update() {
        // Implementation for updating Hospitals status
        return true;
    }
}
