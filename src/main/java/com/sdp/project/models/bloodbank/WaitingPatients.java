package com.sdp.project.models.bloodbank;

import com.sdp.project.models.Person;

import java.util.*;

public class WaitingPatients implements IBeneficiary {

    private final String name;
    private final Queue<Person> patientQueue;

    public WaitingPatients(String name) {
        this.name = name;
        this.patientQueue = new LinkedList<>();
    }

    @Override
    public void update(BloodStock bloodStock) {
        System.out.println(name + " received blood stock update: " + bloodStock.getBloodAmount());
        // Notify patients if their required blood type becomes available
    }

    @Override
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

