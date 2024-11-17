package com.sdp.project.services;

import org.springframework.stereotype.Service;

import com.sdp.project.models.blood.*;
import com.sdp.project.repositories.BeneficiaryRepository;

import java.util.List;

@Service
public class BloodStockService {

    private final BeneficiaryRepository beneficiaryRepository;

    public BloodStockService(BeneficiaryRepository beneficiaryRepository) {
        this.beneficiaryRepository = beneficiaryRepository;
    }

    public void registerBeneficiary(String name, String type) {
        IBeneficiary beneficiary = createBeneficiary(name, type);
        if (beneficiary != null) {
            beneficiaryRepository.addBeneficiary(beneficiary);
        } else {
            throw new IllegalArgumentException("Invalid beneficiary type: " + type);
        }
    }

    public List<IBeneficiary> getAllBeneficiaries() {
        return beneficiaryRepository.getAllBeneficiaries();
    }

    public void notifyAllBeneficiaries(BloodStock bloodStock) {
        for (IBeneficiary beneficiary : beneficiaryRepository.getAllBeneficiaries()) {
            beneficiary.update(bloodStock);
        }
    }



    // Factory method to create the appropriate type of Beneficiary
    private IBeneficiary createBeneficiary(String name, String type) {
        return switch (type.toLowerCase()) {
            case "bloodbank" -> new BloodBank(name);
            case "waitingpatients" -> new WaitingPatient(name);
            case "hospitals" -> new Hospital(name);
            default -> null;
        };
    }

    public void addBlood(BloodType type, int amount) {
        BloodStock.getInstance().addBlood(type, amount);
        notifyAllBeneficiaries(BloodStock.getInstance());
    }

    public void withdrawBlood(BloodType type, int amount) {
        BloodStock.getInstance().withdrawBlood(type, amount);
        notifyAllBeneficiaries(BloodStock.getInstance());
    }
}
