package com.blooddonation.system.models;

import lombok.Getter;

@Getter
public class Address {
    private final String name;
    private final Address parent;

    public Address(String name, Address parent) {
        this.name = name;
        this.parent = parent;
    }

    // Helper method to print the full address in a readable format
    public String getFullAddress() {
        StringBuilder fullAddress = new StringBuilder(name);
        Address current = parent;
        while (current != null) {
            fullAddress.append(", ").append(current.getName());
            current = current.getParent();
        }
        return fullAddress.toString();
    }

    public static Address getFromString(String fullAddress) {
        String[] addressParts = fullAddress.split(",");
        Address parent = null;
        Address current = null;
        for (int i = addressParts.length - 1; i >= 0; i--) {
            current = new Address(addressParts[i].trim(), parent);
            parent = current;
        }
        return current;
    }
}
