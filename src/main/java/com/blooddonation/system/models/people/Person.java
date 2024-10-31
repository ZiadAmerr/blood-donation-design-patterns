package com.blooddonation.system.models.people;

import com.blooddonation.system.models.Address;

import org.jetbrains.annotations.*;

import lombok.Getter;
import lombok.Setter;

import java.util.Set;
import java.util.HashSet;

@Getter
@Setter
@SuppressWarnings("unused")
public abstract class Person {
    private int id;
    private String name;
    private String email;
    private String phone;
    private Address address;
    private static int idCounter = 0;

    private final static Set<Person> people = new HashSet<>();

    public Person(String name, String email, String phone, Address address) {
        this.id = idCounter++;

        this.name = name;
        this.email = email;
        this.phone = phone;
        this.address = address;

        people.add(this);
    }

    public Person() {
        this.id = idCounter++;

        people.add(this);
    }

    @Nullable
    @Contract(pure = true)
    public static Person getPersonById(int id) {
        for (Person person : people)
            if (person.getId() == id)
                return person;
        return null;
    }
}
