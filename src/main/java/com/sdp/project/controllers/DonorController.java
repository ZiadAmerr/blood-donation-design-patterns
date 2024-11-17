package com.sdp.project.controllers;

import com.sdp.project.models.Donor;
import com.sdp.project.services.DonorService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
@RequestMapping("/donors")
public class DonorController {

    @Autowired
    private DonorService donorService;

    @GetMapping("/create")
    public String showCreateDonorForm(Model model) {
        model.addAttribute("donor", new Donor());
        return "createDonor.html"; // Points to createDonor.html.html
    }

    @PostMapping("/create")
    public String createDonor(@ModelAttribute Donor donor, Model model) {
        donorService.saveDonor(donor);
        model.addAttribute("message", "Donor created successfully!");
        return "redirect:/donors/list"; // Redirect to donor list or main page
    }

    @GetMapping("/list")
    public String listDonors(Model model) {
        model.addAttribute("donors", donorService.getAllDonors());
        return "listDonors"; // Points to listDonors.html
    }
}
