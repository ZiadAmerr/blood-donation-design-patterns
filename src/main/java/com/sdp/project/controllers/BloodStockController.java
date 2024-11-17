package com.sdp.project.controllers;

import com.sdp.project.models.blood.*;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import com.sdp.project.services.*;

@Controller
@RequestMapping("/bloodstock")
public class BloodStockController {

    @Autowired
    private BloodStockService bloodStockService;

    @GetMapping
    public String viewStock(Model model) {
        model.addAttribute("bloodStock", BloodStock.getInstance());
        return "bloodstock";
    }

    @PostMapping("/addBlood")
    public String addBlood(@RequestParam("type") BloodType type, @RequestParam("amount") int amount) {
        bloodStockService.addBlood(type, amount);
        return "redirect:/bloodstock";
    }

    @PostMapping("/withdrawBlood")
    public String withdrawBlood(@RequestParam("type") BloodType type, @RequestParam("amount") int amount) {
        bloodStockService.withdrawBlood(type, amount);
        return "redirect:/bloodstock";
    }

    @PostMapping("/addBeneficiary")
    public String addBeneficiary(@RequestParam("name") String name, @RequestParam("type") String type) {
        BloodStock bloodStock = BloodStock.getInstance();

        IBeneficiary beneficiary;
        switch (type) {
            case "BloodBank":
                beneficiary = new BloodBank(name);
                break;
            case "Hospital":
                beneficiary = new Hospital(name);
                break;
            case "WaitingPatient":
                beneficiary = new WaitingPatient(name);
                break;
            default:
                throw new IllegalArgumentException("Invalid beneficiary type: " + type);
        }

        bloodStock.addBeneficiary(beneficiary);
        return "redirect:/bloodstock";
    }


}
