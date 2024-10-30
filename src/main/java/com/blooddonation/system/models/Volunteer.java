package com.blooddonation.system.models;

import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
public class Volunteer extends Person {
    private String occupation;
    private String bloodGroup;
    private String lastDonationDate;

    public Volunteer(String name, String email, String phone, Address address, String occupation, String bloodGroup, String lastDonationDate) {
        super(name, email, phone, address);
        this.occupation = occupation;
        this.bloodGroup = bloodGroup;
        this.lastDonationDate = lastDonationDate;
    }

    public Volunteer() {
    }
}
