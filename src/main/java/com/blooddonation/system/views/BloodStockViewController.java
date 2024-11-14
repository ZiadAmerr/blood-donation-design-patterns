package com.blooddonation.system.views;

import ch.qos.logback.core.model.Model;
import com.blooddonation.system.Repository.BloodStockRepository;
import com.blooddonation.system.models.donations.BloodDonation.BloodStock;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
@RequestMapping("/bloodstock")
public class BloodStockViewController {

    @Autowired
    private BloodStockRepository bloodStockRepository;

    @GetMapping("/")
    public String showBloodStockPage(Model model) {
        BloodStock bloodStock = bloodStockRepository.findById(1L).orElseThrow();
        model.addText("bloodAmounts");
        return "bloodstock"; // points to bloodstock.html in src/main/resources/templates
    }

//    @GetMapping("/beneficiaries")
//    public String showBeneficiaryPage(Model model) {
//        // Add logic to get all beneficiaries
//        model.addAttribute("beneficiaries", /* List of beneficiaries */);
//        return "beneficiaries"; // points to beneficiaries.html in templates
//    }
}
