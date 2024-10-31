package com.blooddonation.system.models.people;

import com.blooddonation.system.models.Address;
import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
@SuppressWarnings("unused")
public class Volunteer extends Person {
    private String occupation;
    private String bloodGroup;
    private String lastDonationDate;

    public Volunteer(
        String name,
        String email,
        String phone, Address address,
        String occupation,
        String bloodGroup,
        String lastDonationDate
    ) {
        super(name, email, phone, address);
        this.occupation = occupation;
        this.bloodGroup = bloodGroup;
        this.lastDonationDate = lastDonationDate;
    }

    public Volunteer() {
    }
}
