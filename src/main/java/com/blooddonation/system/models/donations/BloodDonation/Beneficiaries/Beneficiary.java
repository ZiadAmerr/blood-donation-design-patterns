package com.blooddonation.system.models.donations.BloodDonation.Beneficiaries;

import com.blooddonation.system.models.donations.BloodDonation.BloodDonation;
import com.blooddonation.system.models.donations.BloodDonation.BloodTypeEnum;

public interface Beneficiary {
    boolean receiveBloodDonation(BloodTypeEnum bloodType, int Amount);
}
