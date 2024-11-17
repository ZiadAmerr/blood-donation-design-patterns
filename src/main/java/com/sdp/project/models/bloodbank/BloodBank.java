package com.sdp.project.models.bloodbank;

public class BloodBank {
    private String name;
    private String location;
    private BloodStock bloodStock;

    public BloodBank(String name, String location) {
        this.name = name;
        this.location = location;
        this.bloodStock = new BloodStock();
    }

    // Getters and Setters
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }

    public BloodStock getBloodStock() {
        return bloodStock;
    }

    public void setBloodStock(BloodStock bloodStock) {
        this.bloodStock = bloodStock;
    }

    public void addBeneficiary(String beneficiary) {
        bloodStock.addBeneficiary(beneficiary);
    }

    public void removeBeneficiary(String beneficiary) {
        bloodStock.removeBeneficiary(beneficiary);
    }
}
