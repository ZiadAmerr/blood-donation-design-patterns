package com.sdp.project.controllers;

import com.sdp.project.models.Donor;
import com.sdp.project.models.money.Cash;
import com.sdp.project.models.money.MoneyDonationStrategy;
import com.sdp.project.models.money.Online;
import com.sdp.project.repositories.DonorRepository;
import com.sdp.project.services.DonationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/donations")
public class DonationController {

    @Autowired
    private DonationService donationService;

    @Autowired
    private DonorRepository donorRepository;

    @GetMapping("/new-donor")
    public String showDonorForm(Model model) {
        model.addAttribute("donor", new Donor());
        return "new-donor";
    }

    @PostMapping("/new-donor")
    public String createDonor(@ModelAttribute Donor donor) {
        donorRepository.save(donor);
        return "redirect:/donations";
    }

    @GetMapping
    public String showDonationsPage(Model model) {
        model.addAttribute("donors", donorRepository.findAll());
        return "donations";
    }

    @PostMapping("/blood-donation")
    public String addBloodDonation(
            @RequestParam Integer donorId,
            @RequestParam int volume) {
        donationService.addBloodDonation(donorId, volume);
        return "redirect:/donations";
    }

    @PostMapping("/money-donation")
    public String addMoneyDonation(
            @RequestParam Integer donorId,
            @RequestParam float amount,
            @RequestParam String paymentMethod) {
        MoneyDonationStrategy moneyDonationStrategy;

        // Select payment strategy at runtime
        if ("Cash".equalsIgnoreCase(paymentMethod)) {
            moneyDonationStrategy = new Cash();
        } else if ("Online".equalsIgnoreCase(paymentMethod)) {
            moneyDonationStrategy = new Online();
        } else {
            throw new IllegalArgumentException("Invalid payment method: " + paymentMethod);
        }

        donationService.addMoneyDonation(donorId, amount, moneyDonationStrategy);
        return "redirect:/donations";
    }
}
