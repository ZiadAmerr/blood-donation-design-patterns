-- Drop dependent tables first
DROP TABLE IF EXISTS `blooddonation`;
DROP TABLE IF EXISTS `bloodstock`;
DROP TABLE IF EXISTS `donation`;
DROP TABLE IF EXISTS `outreach_event_activities`;
DROP TABLE IF EXISTS `outreach_event_organizations`;
DROP TABLE IF EXISTS `workshop_event_workshops`;
DROP TABLE IF EXISTS `tickets`;

-- Drop tables with foreign key dependencies
DROP TABLE IF EXISTS `fundraiserevents`;
DROP TABLE IF EXISTS `outreachevents`;
DROP TABLE IF EXISTS `workshopevents`;

DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `attendees`;
DROP TABLE IF EXISTS `workshops`;
DROP TABLE IF EXISTS `organizations`;
DROP TABLE IF EXISTS `donationcampaigns`;
DROP TABLE IF EXISTS `donationcomponents`;

-- Finally, drop the base tables
DROP TABLE IF EXISTS `donor_diseases`;
DROP TABLE IF EXISTS `diseases`;
DROP TABLE IF EXISTS `donors`;
DROP TABLE IF EXISTS `persons`;
DROP TABLE IF EXISTS `addresses`;

-- Create addresses table first
CREATE TABLE `addresses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `parent_id` INT DEFAULT NULL,
    FOREIGN KEY (`parent_id`) REFERENCES `addresses`(`id`) ON DELETE SET NULL,
    UNIQUE KEY unique_parent_name (`parent_id`, `name`)
);

-- Now create persons table
CREATE TABLE `persons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `date_of_birth` DATE NOT NULL,
    `phone_number` VARCHAR(20) NOT NULL UNIQUE,
    `national_id` VARCHAR(50) NOT NULL UNIQUE,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `hashed_password` VARCHAR(255) NOT NULL,
    `address_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`) ON DELETE CASCADE
);

-- Finally, create donors table
CREATE TABLE `donors` (
    `person_id` INT PRIMARY KEY,
    `blood_type` ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
    `weight` DECIMAL(5,2) NOT NULL CHECK (`weight` >= 0),
    FOREIGN KEY (`person_id`) REFERENCES `persons`(`id`) ON DELETE CASCADE
);

-- Create the diseases table
CREATE TABLE `diseases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL UNIQUE,
  `prevents` BOOLEAN NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

-- Create the donor_diseases table (Many-to-Many relationship)
CREATE TABLE `donor_diseases` (
  `donor_id` int(11) NOT NULL,
  `disease_id` int(11) NOT NULL,
  PRIMARY KEY (`donor_id`, `disease_id`),
  FOREIGN KEY (`donor_id`) REFERENCES `donors`(`person_id`) ON DELETE CASCADE,
  FOREIGN KEY (`disease_id`) REFERENCES `diseases`(`id`) ON DELETE CASCADE
);

INSERT INTO `diseases` (`name`, `prevents`) VALUES
    ('HIV', TRUE),
    ('Hepatitis B', TRUE),
    ('Hepatitis C', TRUE),
    ('Syphilis', TRUE),
    ('Malaria', TRUE),
    ('Diabetes', FALSE),
    ('Hypertension', FALSE),
    ('Common Cold', FALSE),
    ('Flu', FALSE),
    ('Anemia', TRUE),
    ('Thalassemia', TRUE),
    ('Epilepsy', TRUE),
    ('Asthma', FALSE),
    ('Recent Surgery', TRUE),
    ('Iron Deficiency', FALSE);


-- Table: donationcomponents
CREATE TABLE donationcomponents (
    id INT AUTO_INCREMENT PRIMARY KEY
);

-- Table: donationcampaigns
CREATE TABLE donationcampaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    parent_campaign_id INT,  -- Self-referencing foreign key to allow nesting of campaigns
    FOREIGN KEY (parent_campaign_id) REFERENCES donationcampaigns(id)  -- Self-referencing foreign key
);

-- Table: events
CREATE TABLE `events` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    maxattendees INT NOT NULL,
    datetime DATETIME NOT NULL,
    address_id INT,  -- Foreign key reference to addresses
    FOREIGN KEY (address_id) REFERENCES addresses(id)
);

-- Table: fundraiserevents
CREATE TABLE fundraiserevents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,  -- Foreign key reference to events
    goalamount DECIMAL(10, 2) NOT NULL,
    raisedamount DECIMAL(10, 2) DEFAULT 0,
    FOREIGN KEY (event_id) REFERENCES `events`(id)
);

-- Table: outreachevents
CREATE TABLE outreachevents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,  -- Foreign key reference to events
    FOREIGN KEY (event_id) REFERENCES `events`(id)
);

-- Table: workshopevents
CREATE TABLE workshopevents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,  -- Foreign key reference to events
    FOREIGN KEY (event_id) REFERENCES `events`(id)
);

-- Table: workshops
CREATE TABLE workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic VARCHAR(255) NOT NULL
);

-- Table: attendees
CREATE TABLE attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    national_id VARCHAR(255) NOT NULL,
    address_id INT,  -- Foreign key reference to addresses
    phonenumber VARCHAR(255) NOT NULL,
    FOREIGN KEY (address_id) REFERENCES addresses(id)
);

-- Table: tickets
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attendee_id INT,  -- Foreign key reference to attendees
    event_id INT,     -- Foreign key reference to events
    FOREIGN KEY (attendee_id) REFERENCES attendees(id),
    FOREIGN KEY (event_id) REFERENCES `events`(id)
);

-- Table: organizations
CREATE TABLE organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address_id INT,  -- Foreign key reference to addresses
    contactnumber VARCHAR(255),
    email VARCHAR(255),
    website VARCHAR(255),
    FOREIGN KEY (address_id) REFERENCES addresses(id)
);

-- Junction table to represent the many-to-many relationship between outreachevents and activities
CREATE TABLE outreach_event_activities (
    outreach_event_id INT, 
    activity VARCHAR(255) NOT NULL,
    FOREIGN KEY (outreach_event_id) REFERENCES outreachevents(id)
);

-- Junction table to represent the many-to-many relationship between outreachevents and organizations
CREATE TABLE outreach_event_organizations (
    outreach_event_id INT,
    organization_id INT,
    FOREIGN KEY (outreach_event_id) REFERENCES outreachevents(id),
    FOREIGN KEY (organization_id) REFERENCES organizations(id)
);

-- Junction table to represent the many-to-many relationship between workshopevents and workshops
CREATE TABLE workshop_event_workshops (
    workshopevent_id INT,
    workshop_id INT,
    FOREIGN KEY (workshopevent_id) REFERENCES workshopevents(id),
    FOREIGN KEY (workshop_id) REFERENCES workshops(id)
);

-- Create table `blooddonation` with indexes, AUTO_INCREMENT, and foreign key constraint
CREATE TABLE `blooddonation` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `donor_id` int(11) NOT NULL,
    `number_of_liters` float NOT NULL,
    `blooddonationtype` enum('Blood','Plasma') NOT NULL,
    `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `donor_id` (`donor_id`),
    FOREIGN KEY (`donor_id`) REFERENCES `donors`(`person_id`) ON DELETE CASCADE
);

-- Create table `bloodstock` with primary key
CREATE TABLE `bloodstock` (
    `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    `amount` float NOT NULL,
    `is_plasma` tinyint(1) NOT NULL,
    PRIMARY KEY (`blood_type`, `is_plasma`)
);

-- Create table `donation` with indexes, AUTO_INCREMENT, and foreign key constraint
CREATE TABLE `donation` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `donor_id` int(11) NOT NULL,
    `type` enum('Blood','Money') NOT NULL,
    `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `donor_id` (`donor_id`),
    FOREIGN KEY (`donor_id`) REFERENCES `donors`(`person_id`) ON DELETE CASCADE
);