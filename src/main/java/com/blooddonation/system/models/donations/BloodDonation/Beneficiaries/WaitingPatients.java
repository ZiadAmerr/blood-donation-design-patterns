package com.blooddonation.system.models.donations.BloodDonation.Beneficiaries;

import com.blooddonation.system.models.donations.BloodDonation.IBloodStock;

public class WaitingPatients implements Beneficiary {
    private IBloodStock bloodStock;

    public WaitingPatients(IBloodStock bloodStock)
    {
        this.bloodStock = bloodStock;
        bloodStock.registerBeneficiary(this);
    }
    @Override
    public boolean update() {
        // Implementation for updating WaitingPatients status
        return true;
    }
}
