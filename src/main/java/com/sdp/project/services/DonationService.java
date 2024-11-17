package com.sdp.project.services;

import com.sdp.project.models.BloodDonation;
import com.sdp.project.models.Donor;
import com.sdp.project.models.money.MoneyDonation;
import com.sdp.project.models.money.MoneyDonationStrategy;
import com.sdp.project.repositories.BloodDonationRepository;
import com.sdp.project.repositories.DonorRepository;
import com.sdp.project.repositories.MoneyDonationRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Optional;

@Service
public class DonationService {

    @Autowired
    private DonorRepository donorRepository;

    @Autowired
    private BloodDonationRepository bloodDonationRepository;

    @Autowired
    private MoneyDonationRepository moneyDonationRepository;

    public void addBloodDonation(Integer donorId, int volume) {
        Optional<Donor> donor = donorRepository.findById(donorId);
        if (donor.isPresent()) {
            BloodDonation bloodDonation = new BloodDonation();
            bloodDonation.setDonorId(donorId);
            bloodDonation.setVolume(volume);
            bloodDonationRepository.save(bloodDonation);
        } else {
            throw new IllegalArgumentException("Donor not found with ID: " + donorId);
        }
    }

    public void addMoneyDonation(Integer donorId, float amount, MoneyDonationStrategy strategy) {
        Optional<Donor> donor = donorRepository.findById(donorId);
        if (donor.isPresent()) {
            boolean success = strategy.donate(amount);
            MoneyDonation moneyDonation = new MoneyDonation();
            moneyDonation.setDonorId(donorId);

            Date currentDate = new Date();
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");  // You can customize the date format
            String formattedDate = sdf.format(currentDate);
            moneyDonation.setDonationDate(formattedDate);

            moneyDonation.setAmount(amount);

            moneyDonationRepository.save(moneyDonation);
        } else {
            throw new IllegalArgumentException("Donor not found with ID: " + donorId);
        }
    }
}
