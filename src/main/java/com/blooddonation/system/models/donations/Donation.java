package com.blooddonation.system.models.donations;
import com.blooddonation.system.models.people.Donor;

import lombok.Getter;

@Getter
@SuppressWarnings("unused")
public abstract class Donation {
    private static int idCounter = 0;
    private final int id;
    private final Donor donor;

    public Donation(Donor donor) {
        this.id = idCounter++;
        this.donor = donor;
    }
}
