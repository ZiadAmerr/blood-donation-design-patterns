package com.blooddonation.system.models.people;

import com.blooddonation.system.models.Address;
import com.blooddonation.system.models.BloodGroup;
import lombok.Getter;
import lombok.Setter;

import java.util.Date;

@Getter
@Setter
@SuppressWarnings("unused")
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
