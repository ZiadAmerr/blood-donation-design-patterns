package com.blooddonation.system.models.donations.BloodDonation;

import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.Beneficiary;
import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.*;
import jakarta.persistence.*;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

@Entity
@Embeddable
public class BloodStock implements IBloodStock {

    // List of hospitals that depend on the blood stock
    @OneToMany(mappedBy = "bloodStock", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<Hospital> hospitals;

    // List of waiting patients who need blood
    @OneToMany(mappedBy = "bloodStock", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<WaitingPatient> waitingPatients;

    // List of blood banks linked to this blood stock
    @OneToMany(mappedBy = "bloodStock", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private List<BloodBank> bloodBanks;

    @Id
    private Long id;

    @ElementCollection
    @CollectionTable(name = "blood_amount", joinColumns = @JoinColumn(name = "blood_stock_id"))
    @MapKeyEnumerated(EnumType.STRING)
    @Column(name = "amount")
    private Map<BloodTypeEnum, Integer> bloodAmount;

    // Singleton pattern for BloodStock instance
    private static BloodStock instance;

    // Public no-arg constructor for JPA
    // SHOULD BE PRIVATE BUT JPA MADE ME DO THIS
    protected BloodStock() {
        hospitals = new ArrayList<>();
        waitingPatients = new ArrayList<>();
        bloodBanks = new ArrayList<>();
        bloodAmount = new HashMap<>();
    }

    public static BloodStock getInstance() {
        if (instance == null) {
            instance = new BloodStock();
        }
        return instance;
    }

    public Map<BloodTypeEnum, Integer> getBloodAmount() {
        return bloodAmount;
    }

    // Increase the blood amount for a specific blood type
    public void increaseBloodAmount(BloodTypeEnum bloodType, int liters) {
        int currentAmount = bloodAmount.getOrDefault(bloodType, 0);
        bloodAmount.put(bloodType, currentAmount + liters);
        notifyBeneficiaries(bloodType, currentAmount + liters);
    }

    // Method to decrease the blood amount for a specific blood type
    public void decreaseBloodAmount(BloodTypeEnum bloodType, int liters) {
        if (liters < 0) {
            throw new IllegalArgumentException("Amount to decrease must be positive.");
        }

        int currentAmount = bloodAmount.getOrDefault(bloodType, 0);

        if (currentAmount < liters) {
            throw new IllegalArgumentException("Insufficient blood amount for the given type.");
        }
        bloodAmount.put(bloodType, currentAmount - liters);
        notifyBeneficiaries(bloodType, currentAmount - liters);
    }

    @Override
    public void registerBeneficiary(Beneficiary beneficiary) {
        if (beneficiary instanceof Hospital) {
            // If the beneficiary is a Hospital, add it to the hospital list
            hospitals.add((Hospital) beneficiary);
        } else if (beneficiary instanceof WaitingPatient) {
            // If the beneficiary is a WaitingPatient, add it to the waitingPatients list
            waitingPatients.add((WaitingPatient) beneficiary);
        } else if (beneficiary instanceof BloodBank) {
            // If the beneficiary is a BloodBank, add it to the bloodBanks list
            bloodBanks.add((BloodBank) beneficiary);
        }
    }


    @Override
    public void removeBeneficiary(Beneficiary beneficiary) {
        if (beneficiary instanceof Hospital) {
            // If the beneficiary is a Hospital, add it to the hospital list
            hospitals.remove((Hospital) beneficiary);
        } else if (beneficiary instanceof WaitingPatient) {
            // If the beneficiary is a WaitingPatient, add it to the waitingPatients list
            waitingPatients.remove((WaitingPatient) beneficiary);
        } else if (beneficiary instanceof BloodBank) {
            // If the beneficiary is a BloodBank, add it to the bloodBanks list
            bloodBanks.remove((BloodBank) beneficiary);
        }
    }

    @Override
    public void notifyBeneficiaries(BloodTypeEnum bloodType, int newAmount) {
        // Notify hospitals
        for (Beneficiary beneficiary : hospitals) {
            if (beneficiary instanceof Hospital) {
                ((Hospital) beneficiary).update(bloodType, newAmount);
            }
        }

        // Notify waiting patients
        for (Beneficiary beneficiary : waitingPatients) {
            if (beneficiary instanceof WaitingPatient) {
                ((WaitingPatient) beneficiary).update(bloodType, newAmount);
            }
        }

        // Notify blood banks
        for (Beneficiary beneficiary : bloodBanks) {
            if (beneficiary instanceof BloodBank) {
                ((BloodBank) beneficiary).update(bloodType, newAmount);
            }
        }
    }


    // Methods to handle hospitals, waiting patients, and blood banks
    public void registerHospital(Hospital hospital) {
        hospitals.add(hospital);
    }

    public void removeHospital(Hospital hospital) {
        hospitals.remove(hospital);
    }

    public void registerWaitingPatient(WaitingPatient patient) {
        waitingPatients.add(patient);
    }

    public void removeWaitingPatient(WaitingPatient patient) {
        waitingPatients.remove(patient);
    }

    public void registerBloodBank(BloodBank bloodBank) {
        bloodBanks.add(bloodBank);
    }

    public void removeBloodBank(BloodBank bloodBank) {
        bloodBanks.remove(bloodBank);
    }
}
