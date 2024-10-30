package com.blooddonation.system.models;

import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
public class Employee extends Person {
    private int employeeId;
    private int privilege;
    private static int employeeCounter = 0;

    public Employee(String name, String email, String phone, Address address) {
        super(name, email, phone, address);
        this.employeeId = ++employeeCounter;
        this.privilege = 0;
    }

    public Employee() {
    }
}
