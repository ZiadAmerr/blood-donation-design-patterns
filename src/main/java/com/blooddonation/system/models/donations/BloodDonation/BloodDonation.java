package com.blooddonation.system.models.donations.BloodDonation;
import com.blooddonation.system.models.people.Donor;
import com.blooddonation.system.models.donations.Donation;
import com.blooddonation.system.models.donations.BloodDonation.BloodStock;

public class BloodDonation extends Donation {
    private int numberOfLiters;
    private BloodTypeEnum bloodType;

    public BloodDonation(Donor donor, int numberOfLiters, BloodTypeEnum bloodType) {
        super(donor);  // Call the constructor of the parent class Donation
        this.numberOfLiters = numberOfLiters;
        this.bloodType = bloodType;
    }

    public int getNumberOfLiters() {
        return numberOfLiters;
    }

    public void setNumberOfLiters(int numberOfLiters) {
        this.numberOfLiters = numberOfLiters;
    }

    public BloodTypeEnum getBloodType() {
        return bloodType;
    }

    public void setBloodType(BloodTypeEnum bloodType) {
        this.bloodType = bloodType;
    }

    public boolean increaseBloodStock(BloodStock bloodStock) {
        int currentAmount = bloodStock.getBloodAmount().getOrDefault(bloodType, 0);
        bloodStock.getBloodAmount().put(bloodType, currentAmount + numberOfLiters);
        bloodStock.notifyBeneficiaries();
        return true;
    }
}

