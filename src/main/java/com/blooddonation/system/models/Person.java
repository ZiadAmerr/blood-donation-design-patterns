package com.blooddonation.system.models;

import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
public abstract class Person {
    private String name;
    private String email;
    private String phone;
    private Address address;

    public Person(String name, String email, String phone, Address address) {
        this.name = name;
        this.email = email;
        this.phone = phone;
        this.address = address;
    }

    public Person() {
    }

}
