package com.blooddonation.system.models.people;

import com.blooddonation.system.models.Address;
import com.blooddonation.system.models.BloodGroup;
import com.blooddonation.system.models.donations.Donation;

import lombok.Getter;
import lombok.Setter;

import java.util.HashSet;
import java.util.Set;

@Getter
@SuppressWarnings("unused")
public class Donor extends Person {
    private @Setter BloodGroup bloodGroup;
    private final Set<Donation> donations = new HashSet<>();

    public Donor(String name, String email, String phone, Address address, BloodGroup bloodGroup) {
        super(name, email, phone, address);
        this.bloodGroup = bloodGroup;
    }
    // To be modified
    private boolean makeDonation() {
        return true;
    }
}
