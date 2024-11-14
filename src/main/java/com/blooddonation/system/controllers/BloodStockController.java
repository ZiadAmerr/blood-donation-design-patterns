package com.blooddonation.system.controllers;

import com.blooddonation.system.models.donations.BloodDonation.BloodStock;
import com.blooddonation.system.models.donations.BloodDonation.BloodTypeEnum;
import com.blooddonation.system.models.donations.BloodDonation.Beneficiaries.Beneficiary;
import com.blooddonation.system.Repository.BloodStockRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/bloodstock")
public class BloodStockController {

    @Autowired
    private BloodStockRepository bloodStockRepository;

    // Get the current BloodStock instance (Assuming there's only one instance)
    private BloodStock getBloodStock() {
        Optional<BloodStock> bloodStock = bloodStockRepository.findById(1L); // Single instance for simplicity
        return bloodStock.orElseThrow(() -> new IllegalStateException("BloodStock not found"));
    }

    // Get current blood amounts
    @GetMapping("/amounts")
    public Map<BloodTypeEnum, Integer> getBloodAmounts() {
        return getBloodStock().getBloodAmount();
    }

    // Increase blood amount
    @PostMapping("/increase")
    public void increaseBloodAmount(@RequestParam("bloodType") BloodTypeEnum bloodType, @RequestParam("liters") int liters) {
        BloodStock bloodStock = getBloodStock();
        bloodStock.increaseBloodAmount(bloodType, liters);
        bloodStockRepository.save(bloodStock); // Persist changes
    }

    // Decrease blood amount
    @PostMapping("/decrease")
    public void decreaseBloodAmount(@RequestParam("bloodType") BloodTypeEnum bloodType, @RequestParam("liters") int liters) {
        BloodStock bloodStock = getBloodStock();
        bloodStock.decreaseBloodAmount(bloodType, liters);
        bloodStockRepository.save(bloodStock); // Persist changes
    }

    // Register a beneficiary
    @PostMapping("/register-beneficiary")
    public void registerBeneficiary(@RequestBody Beneficiary beneficiary) {
        BloodStock bloodStock = getBloodStock();
        bloodStock.registerBeneficiary(beneficiary);
        bloodStockRepository.save(bloodStock); // Persist changes
    }

    // Remove a beneficiary
    @PostMapping("/remove-beneficiary")
    public void removeBeneficiary(@RequestBody Beneficiary beneficiary) {
        BloodStock bloodStock = getBloodStock();
        bloodStock.removeBeneficiary(beneficiary);
        bloodStockRepository.save(bloodStock); // Persist changes
    }

    // Notify all beneficiaries (this could be triggered manually or based on events)
    @PostMapping("/notify-beneficiaries")
    public ResponseEntity<String> notifyBeneficiaries(@RequestParam BloodTypeEnum bloodType, @RequestParam int newAmount) {
        BloodStock bloodStock = getBloodStock();
        bloodStock.notifyBeneficiaries(bloodType, newAmount);
        return ResponseEntity.ok("Beneficiaries notified successfully.");
    }
}
