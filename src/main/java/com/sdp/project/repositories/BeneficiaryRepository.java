package com.sdp.project.repositories;

import org.springframework.stereotype.Repository;
import java.util.*;

import com.sdp.project.models.bloodbank.IBeneficiary;

@Repository
public class BeneficiaryRepository {

    private final List<IBeneficiary> beneficiaries = new ArrayList<>();

    public void addBeneficiary(IBeneficiary beneficiary) {
        beneficiaries.add(beneficiary);
    }

    public void removeBeneficiary(IBeneficiary beneficiary) {
        beneficiaries.remove(beneficiary);
    }

    public List<IBeneficiary> getAllBeneficiaries() {
        return new ArrayList<>(beneficiaries);
    }
}
