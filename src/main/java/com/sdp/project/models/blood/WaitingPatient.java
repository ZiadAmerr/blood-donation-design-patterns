package com.sdp.project.models.blood;

import com.sdp.project.models.Person;

import java.util.*;

public class WaitingPatient implements IBeneficiary {

    private String name;
    private Queue<Person> patientQueue;

    public WaitingPatient(String name) {
        this.name = name;
        this.patientQueue = new LinkedList<>();
    }

    @Override
    public void update(BloodStock bloodStock) {
        System.out.println(name + " received blood stock update: " + bloodStock.getBloodAmount());
        // Notify patients if their required blood type becomes available
    }

    public String getName() {
        return name;
    }

    // Manage patient queue
    public void addPatient(Person person) {
        patientQueue.offer(person);
    }

    public Person getNextPatient() {
        return patientQueue.poll();
    }
}

