package com.blooddonation.system.models.people;

import com.blooddonation.system.models.Address;
import com.blooddonation.system.models.BloodGroup;
import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
@SuppressWarnings("unused")
public class Recipient extends Person {
    private BloodGroup bloodGroup;
    private String hospitalName;
    private Address hospitalAddress;
    private String hospitalPhone;

    public Recipient(String name, String email, String phone, Address address, BloodGroup bloodGroup, String hospitalName, Address hospitalAddress, String hospitalPhone) {
        super(name, email, phone, address);
        this.bloodGroup = bloodGroup;
        this.hospitalName = hospitalName;
        this.hospitalAddress = hospitalAddress;
        this.hospitalPhone = hospitalPhone;
    }

    public Recipient() {
    }
}
