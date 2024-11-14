package com.blooddonation.system.models.donations.BloodDonation.Beneficiaries;

import com.blooddonation.system.models.donations.BloodDonation.BloodStock;
import com.blooddonation.system.models.donations.BloodDonation.BloodTypeEnum;
import com.blooddonation.system.models.donations.BloodDonation.IBloodStock;
import jakarta.persistence.*;
import java.util.HashMap;
import java.util.Map;

@Entity
public class WaitingPatient implements Beneficiary {

    @Id
    private int id;

    @Embedded
    private IBloodStock bloodStock;

    @Embedded
    private BloodStock bloodBank = BloodStock.getInstance();

    @ElementCollection
    @CollectionTable(name = "waiting_patient_blood_storage", joinColumns = @JoinColumn(name = "patient_id"))
    @MapKeyEnumerated(EnumType.STRING)
    @MapKeyColumn(name = "blood_type")
    @Column(name = "amount")
    private Map<BloodTypeEnum, Integer> bloodStorage = new HashMap<>();

    // Constructor for dependency injection
    public WaitingPatient(IBloodStock bloodStock) {
        this.bloodStock = bloodStock;
        bloodStorage = new HashMap<>();
        bloodStock.registerBeneficiary(this);
    }

    // Default constructor required by JPA
    public WaitingPatient() {}

    @Override
    public void update(BloodTypeEnum bloodType, int newAmount) {
        int currentAmount = bloodStorage.getOrDefault(bloodType, 0);
        bloodStorage.put(bloodType, newAmount);
    }

    @Override
    public boolean receiveBloodDonation(BloodTypeEnum bloodType, int liters) {
        if (liters <= 0) {
            throw new IllegalArgumentException("Liters to receive must be positive.");
        }
        try {
            bloodBank.decreaseBloodAmount(bloodType, liters);
            return true;
        } catch (IllegalArgumentException e) {
            // Handle the case where there's not enough blood in the stock
            System.out.println("Error: " + e.getMessage());
            return false;
        }
    }
}
