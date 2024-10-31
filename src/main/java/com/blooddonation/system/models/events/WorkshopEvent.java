package com.blooddonation.system.models.events;

import com.blooddonation.system.models.people.Person;
import com.blooddonation.system.models.Address;

import lombok.Getter;

import java.util.Arrays;
import java.util.Date;

import java.util.HashSet;
import java.util.Set;

@Getter
@SuppressWarnings("unused")
public class WorkshopEvent extends Event {
    private final Set<Person> instructors = new HashSet<>();

    public WorkshopEvent(String name, String description, Date date, Address address) {
        super(name, description, date, address);
    }

    public WorkshopEvent() {
    }

    public void addInstructors(Person... instructors) {
        this.instructors.addAll(Arrays.asList(instructors));
    }

    public void removeInstructors(Person... instructors) {
        Arrays.asList(instructors).forEach(this.instructors::remove);
    }

    public Set<Person> getInstructors() {
        return new HashSet<>(instructors);
    }

    public boolean hasInstructor(Person instructor) {
        return instructors.contains(instructor);
    }

    public void clearInstructors() {
        instructors.clear();
    }
}
