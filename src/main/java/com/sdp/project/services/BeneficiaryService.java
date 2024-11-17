package com.sdp.project.services;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.sdp.project.models.blood.IBeneficiary;
import com.sdp.project.models.blood.BloodStock;
import com.sdp.project.repositories.BeneficiaryRepository;

import java.util.List;

@Service
public class BeneficiaryService {

    @Autowired
    private BeneficiaryRepository beneficiaryRepository;

    public void registerBeneficiary(IBeneficiary beneficiary) {
        beneficiaryRepository.addBeneficiary(beneficiary);
    }

    public List<IBeneficiary> getAllBeneficiaries() {
        return beneficiaryRepository.getAllBeneficiaries();
    }

    public void notifyAllBeneficiaries(BloodStock bloodStock) {
        for (IBeneficiary beneficiary : beneficiaryRepository.getAllBeneficiaries()) {
            beneficiary.update(bloodStock);
        }
    }
}

