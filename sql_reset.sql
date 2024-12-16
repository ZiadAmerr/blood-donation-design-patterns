USE sdp;


-- Drop all tables in reverse order of dependencies
DROP TABLE IF EXISTS Skills;
DROP TABLE IF EXISTS Volunteer;
DROP TABLE IF EXISTS Item;
DROP TABLE IF EXISTS MoneyDonationDetails;
DROP TABLE IF EXISTS BloodDonation;
DROP TABLE IF EXISTS Donation;
DROP TABLE IF EXISTS Donor;
DROP TABLE IF EXISTS BloodStock;
DROP TABLE IF EXISTS Event;
DROP TABLE IF EXISTS PaymentMethod;
DROP TABLE IF EXISTS Person;
DROP TABLE IF EXISTS Address;

-- Recreate tables
-- Table: Address
CREATE TABLE Address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL
);

-- Table: Person
CREATE TABLE Person (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    address_id INT,
    FOREIGN KEY (address_id) REFERENCES Address(id)
);

-- Table: Donor
CREATE TABLE Donor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    person_id INT NOT NULL,
    FOREIGN KEY (person_id) REFERENCES Person(id)
);

-- Table: Donation
CREATE TABLE Donation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    type ENUM('Blood', 'Money') NOT NULL,
    FOREIGN KEY (donor_id) REFERENCES Donor(id)
);

-- Table: BloodDonation
CREATE TABLE BloodDonation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donation_id INT NOT NULL,
    number_of_liters FLOAT NOT NULL,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-') NOT NULL,
    FOREIGN KEY (donation_id) REFERENCES Donation(id)
);

-- Table: MoneyDonationDetails
CREATE TABLE MoneyDonationDetails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donation_id INT NOT NULL,
    datetime DATETIME NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (donation_id) REFERENCES Donation(id)
);

-- Table: Item
CREATE TABLE Item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    money_donation_id INT NOT NULL,
    FOREIGN KEY (money_donation_id) REFERENCES MoneyDonationDetails(id)
);

-- Table: Volunteer
CREATE TABLE Volunteer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    person_id INT NOT NULL,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES Person(id)
);

-- Table: Skills
CREATE TABLE Skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    volunteer_id INT NOT NULL,
    FOREIGN KEY (volunteer_id) REFERENCES Volunteer(id)
);

-- Table: Event
CREATE TABLE Event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Workshop', 'Fundraiser', 'Outreach') NOT NULL,
    title VARCHAR(255) NOT NULL,
    address_id INT NOT NULL,
    datetime DATETIME NOT NULL,
    FOREIGN KEY (address_id) REFERENCES Address(id)
);

-- Table: BloodStock
CREATE TABLE BloodStock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-') NOT NULL,
    amount FLOAT NOT NULL
);

-- Table: PaymentMethod
CREATE TABLE PaymentMethod (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Credit', 'Debit', 'PayPal', 'BnsPay') NOT NULL,
    card_number VARCHAR(16),
    cvv VARCHAR(4),
    expiry_date DATE,
    email VARCHAR(255),
    password VARCHAR(255)
);

