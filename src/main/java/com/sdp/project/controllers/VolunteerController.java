package com.sdp.project.controllers;

import com.sdp.project.models.volunteers.Volunteer;
import com.sdp.project.repositories.VolunteerRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ModelAttribute;

@Controller
@RequestMapping("/volunteers")
public class VolunteerController {

    @Autowired
    private VolunteerRepository volunteerRepository;

    // Display all volunteers
    @GetMapping
    public String showVolunteerPage(Model model) {
        try {
            Iterable<Volunteer> listOfVolunteers = volunteerRepository.findAll();
            System.out.println("Volunteers Retrieved: " + listOfVolunteers); // Debug log
            model.addAttribute("volunteers", listOfVolunteers);
            return "volunteers";
        } catch (Exception e) {
            e.printStackTrace(); // Log the error
            model.addAttribute("error", "An error occurred while fetching volunteers.");
            return "error";
        }
    }


    // Show Add Volunteer form
    @GetMapping("/add-volunteer-form")
    public String showAddVolunteerForm(Model model) {
        model.addAttribute("volunteer", new Volunteer());  // Add empty volunteer object to the model
        return "add-volunteer";
    }

    // Add new volunteer
    @PostMapping("/add-volunteer")
    public String addVolunteer(@ModelAttribute Volunteer volunteer) {
        try {
            volunteerRepository.save(volunteer);
            return "redirect:/volunteers"; // Redirect to the volunteer list after saving
        } catch (Exception e) {
            e.printStackTrace(); // Log the error
            return "error";
        }
    }
}
