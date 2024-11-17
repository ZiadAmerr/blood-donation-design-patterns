package com.sdp.project.services;


import com.sdp.project.models.BloodDonation;
import com.sdp.project.models.Donor;
import com.sdp.project.models.money.MoneyDonation;
import com.sdp.project.repositories.BloodDonationRepository;
import com.sdp.project.repositories.DonorRepository;
import com.sdp.project.repositories.MoneyDonationRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class DonorService {

    @Autowired
    private DonorRepository donorRepository;

    public void saveDonor(Donor donor) {
        donorRepository.save(donor); // Uses CrudRepository's save method
    }

    public List<Donor> getAllDonors() {
        return (List<Donor>) donorRepository.findAll(); // Cast to List
    }

    public Donor findDonorById(Integer id) {
        return donorRepository.findById(id).orElse(null); // Optional handling
    }
}
