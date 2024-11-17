package com.sdp.project.controllers;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class HomeController {

    // Mapping to home page
    @GetMapping("/")
    public String home() {
        return "index"; // Returns index.html view
    }
}

