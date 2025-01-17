-- Ensure tables are dropped in the correct order to avoid dependency issues
DROP TABLE IF EXISTS `donor_diseases`;
DROP TABLE IF EXISTS `donors`;
DROP TABLE IF EXISTS `persons`;
DROP TABLE IF EXISTS `addresses`;
DROP TABLE IF EXISTS `diseases`;

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
