USE sdp;

-- Drop all tables in reverse order of dependencies
DROP TABLE IF EXISTS VolunteerSkills;
DROP TABLE IF EXISTS Skills;
DROP TABLE IF EXISTS Volunteer;
DROP TABLE IF EXISTS Item;
DROP TABLE IF EXISTS CardDetails;
DROP TABLE IF EXISTS PaymentMethod;
DROP TABLE IF EXISTS MoneyDonationDetails;
DROP TABLE IF EXISTS BloodDonation;
DROP TABLE IF EXISTS Donation;
DROP TABLE IF EXISTS Donor;
DROP TABLE IF EXISTS BloodStock;
DROP TABLE IF EXISTS Event;
DROP TABLE IF EXISTS Person;
DROP TABLE IF EXISTS Address;

-- Table: Address
CREATE TABLE Address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES Address(id) ON DELETE SET NULL,
    UNIQUE KEY unique_parent_name (parent_id, name)
);

-- Table: Person
CREATE TABLE Person (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    national_id VARCHAR(20) UNIQUE NOT NULL,
    address_id INT,
    FOREIGN KEY (address_id) REFERENCES Address(id) ON DELETE SET NULL
);

-- Table: Donor
CREATE TABLE Donor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    person_id INT NOT NULL,
    FOREIGN KEY (person_id) REFERENCES Person(id) ON DELETE CASCADE
);

-- Table: Donation
CREATE TABLE Donation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    type ENUM('Blood', 'Money') NOT NULL,
    FOREIGN KEY (donor_id) REFERENCES Donor(id) ON DELETE CASCADE
);

-- Table: MoneyDonationDetails
CREATE TABLE MoneyDonationDetails (
    donation_id INT PRIMARY KEY, -- donation_id as PRIMARY and FOREIGN KEY
    datetime DATETIME NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (donation_id) REFERENCES Donation(id) ON DELETE CASCADE
);

-- Table: BloodDonation
CREATE TABLE BloodDonation (
    donation_id INT PRIMARY KEY, -- donation_id as PRIMARY and FOREIGN KEY
    number_of_liters FLOAT NOT NULL,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-') NOT NULL,
    FOREIGN KEY (donation_id) REFERENCES Donation(id) ON DELETE CASCADE
);

-- Table: Item
CREATE TABLE Item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    money_donation_id INT NOT NULL,
    FOREIGN KEY (money_donation_id) REFERENCES MoneyDonationDetails(donation_id) ON DELETE CASCADE
);

-- Table: Volunteer
CREATE TABLE Volunteer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    person_id INT NOT NULL,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES Person(id) ON DELETE CASCADE
);

-- Table: Skills
CREATE TABLE Skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- Table: VolunteerSkills (Many-to-Many Table Between Volunteer and Skills)
CREATE TABLE VolunteerSkills (
    volunteer_id INT NOT NULL,
    skill_id INT NOT NULL,
    PRIMARY KEY (volunteer_id, skill_id),
    FOREIGN KEY (volunteer_id) REFERENCES Volunteer(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES Skills(id) ON DELETE CASCADE
);

-- Table: Event
CREATE TABLE Event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Workshop', 'Fundraiser', 'Outreach') NOT NULL,
    title VARCHAR(255) NOT NULL,
    address_id INT NOT NULL,
    datetime DATETIME NOT NULL,
    FOREIGN KEY (address_id) REFERENCES Address(id) ON DELETE CASCADE
);

-- Table: BloodStock
CREATE TABLE BloodStock (
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-') PRIMARY KEY,
    amount FLOAT NOT NULL
);

-- Table: PaymentMethod
CREATE TABLE PaymentMethod (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Credit', 'Debit', 'PayPal', 'BnsPay') NOT NULL,
    email VARCHAR(255), -- For PayPal or BnsPay
    password VARCHAR(255) -- Should be hashed securely
);

-- Table: CardDetails (Normalized sensitive data)
CREATE TABLE CardDetails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_method_id INT NOT NULL,
    card_number VARCHAR(16) NOT NULL,
    cvv VARCHAR(4) NOT NULL,
    expiry_date DATE NOT NULL,
    FOREIGN KEY (payment_method_id) REFERENCES PaymentMethod(id) ON DELETE CASCADE
);
