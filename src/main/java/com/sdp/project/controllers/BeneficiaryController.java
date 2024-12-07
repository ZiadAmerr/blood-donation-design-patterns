package com.sdp.project.controllers;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import com.sdp.project.models.blood.BloodBank;
import com.sdp.project.models.blood.Hospital;
import com.sdp.project.models.blood.WaitingPatient;
import com.sdp.project.models.blood.IBeneficiary;
import com.sdp.project.services.BeneficiaryService;


@Controller
@RequestMapping("/beneficiaries")
public class BeneficiaryController {

    @Autowired
    private BeneficiaryService beneficiaryService;

    @GetMapping
    public String getAllBeneficiaries(Model model) {
        model.addAttribute("beneficiaries", beneficiaryService.getAllBeneficiaries());
        return "beneficiaries";
    }

    @PostMapping("/register")
    public String registerBeneficiary(@RequestParam("name") String name, @RequestParam("type") String type) {
        IBeneficiary beneficiary = switch (type) {
            case "BloodBank" -> new BloodBank(name);
            case "WaitingPatients" -> new WaitingPatient(name);
            case "Hospitals" -> new Hospital(name);
            default -> throw new IllegalArgumentException("Invalid beneficiary type");
        };

        beneficiaryService.registerBeneficiary(beneficiary);
        return "redirect:/beneficiaries";
    }
}
