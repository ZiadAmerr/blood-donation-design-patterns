package com.blooddonation.system.models.donations.BloodDonation;

import com.blooddonation.system.models.donations.Donation;
import com.blooddonation.system.models.people.Donor;
import jakarta.persistence.*;

@Entity
public class BloodDonation extends Donation {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private int id;

    @Column(nullable = false)
    private int numberOfLiters;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private BloodTypeEnum bloodType;

    // Constructor for dependency injection
    public BloodDonation(Donor donor, int numberOfLiters, BloodTypeEnum bloodType) {
        super(donor);
        this.numberOfLiters = numberOfLiters;
        this.bloodType = bloodType;
    }

    // Default constructor required by JPA
    public BloodDonation() {
        super();
    }

    // Getters and setters
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
        bloodStock.increaseBloodAmount(bloodType, numberOfLiters);
        return true;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getId() {
        return id;
    }
}
