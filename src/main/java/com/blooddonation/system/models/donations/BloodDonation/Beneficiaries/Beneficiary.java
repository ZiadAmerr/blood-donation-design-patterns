package com.blooddonation.system.models.donations.BloodDonation.Beneficiaries;

import com.blooddonation.system.models.donations.BloodDonation.BloodDonation;
import com.blooddonation.system.models.donations.BloodDonation.BloodTypeEnum;

import java.util.Map;

public interface Beneficiary {

    void update(BloodTypeEnum bloodType, int newAmount);
    boolean receiveBloodDonation(BloodTypeEnum bloodType, int Amount);
}
