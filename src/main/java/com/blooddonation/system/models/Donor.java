package com.blooddonation.system.models;

import lombok.Getter;
import lombok.Setter;

import java.util.Date;

@Getter
@Setter
public class Donor extends Person {
    private String occupation;
    private BloodGroup bloodGroup;
    private Date lastDonationDate;

    public Donor(String name, String email, String phone, Address address, String occupation, BloodGroup bloodGroup, Date lastDonationDate) {
        super(name, email, phone, address);
        this.occupation = occupation;
        this.bloodGroup = bloodGroup;
        this.lastDonationDate = lastDonationDate;
    }

    public Donor() {
    }
}
