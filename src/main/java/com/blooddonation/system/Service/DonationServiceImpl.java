package com.blooddonation.system.Service;

import com.blooddonation.system.models.donations.Donation;
import com.blooddonation.system.Repository.DonationRepository;
import com.blooddonation.system.Service.DonationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class DonationServiceImpl implements DonationService {

    private final DonationRepository donationRepository;

    @Autowired
    public DonationServiceImpl(DonationRepository donationRepository) {
        this.donationRepository = donationRepository;
    }

    @Override
    public Donation saveDonation(Donation donation) {
        return donationRepository.save(donation);
    }

    @Override
    public Optional<Donation> getDonationById(Long id) {
        return donationRepository.findById(id);
    }

    @Override
    public List<Donation> getAllDonations() {
        return donationRepository.findAll();
    }

    @Override
    public void deleteDonation(Long id) {
        donationRepository.deleteById(id);
    }
}