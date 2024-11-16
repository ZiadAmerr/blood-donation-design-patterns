package com.blooddonation.system.controllers;

import com.blooddonation.system.models.donations.MoneyDonation.MoneyDonation;
import com.blooddonation.system.models.donations.MoneyDonation.MoneyDonationDetails;
import com.blooddonation.system.models.donations.DonationMethod;
import com.blooddonation.system.models.people.Donor;
import org.springframework.web.bind.annotation.*;

import java.util.ArrayList;
import java.util.List;

@RestController
@RequestMapping("/money-donations")
public class MoneyDonationController {

    private List<MoneyDonation> donations = new ArrayList<>();

    @PostMapping
    public MoneyDonation createDonation(@RequestBody MoneyDonationDetails donationDetails, @RequestParam DonationMethod donationMethod, @RequestParam Donor donor) {
        MoneyDonation donation = new MoneyDonation(donationMethod, donationDetails, donor);
        donations.add(donation);
        return donation;
    }

    @GetMapping
    public List<MoneyDonation> getAllDonations() {
        return donations;
    }

    @GetMapping("/{id}")
    public MoneyDonation getDonationById(@PathVariable int id) {
        return donations.get(id);
    }

    @PutMapping("/{id}")
    public MoneyDonation updateDonation(@PathVariable int id, @RequestBody MoneyDonationDetails donationDetails, @RequestParam DonationMethod donationMethod, @RequestParam Donor donor) {
        MoneyDonation existingDonation = donations.get(id);
        existingDonation.setDonationMethod(donationMethod);
        existingDonation.setMoneyDonationDetails(donationDetails);
        existingDonation.setDonor(donor);
        return existingDonation;
    }

    @DeleteMapping("/{id}")
    public void deleteDonation(@PathVariable int id) {
        donations.remove(id);
    }
}