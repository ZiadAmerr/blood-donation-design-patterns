package com.sdp.project.models.blood;

public enum BloodType {
    A_POS, A_NEG, B_POS, B_NEG, AB_POS, AB_NEG, O_POS, O_NEG;

    public boolean canDonateTo(BloodType other) {
        if (this == other) {
            return true;
        }
        return switch (this) {
            case A_POS -> other == AB_POS || other == A_NEG || other == AB_NEG;
            case B_POS -> other == AB_POS || other == B_NEG || other == AB_NEG;
            case A_NEG, B_NEG, AB_POS -> other == AB_NEG;
            case AB_NEG -> false;
            case O_POS -> true;
            case O_NEG -> other == A_NEG || other == B_NEG || other == AB_NEG;
        };
    }

    public boolean canReceiveFrom(BloodType other) {
        return other.canDonateTo(this);
    }

    @Override
    public String toString() {
        return name().replace("_", "+");
    }
}

