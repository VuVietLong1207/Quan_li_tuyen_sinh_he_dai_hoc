-- Schema creation script for admission_management (safe: IF NOT EXISTS)
-- Run in phpMyAdmin or mysql CLI connected to database `admission_management`.

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `full_name` VARCHAR(255) DEFAULT NULL,
  `user_type` ENUM('candidate','admin') NOT NULL DEFAULT 'candidate',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `majors` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `major_name` VARCHAR(255) NOT NULL,
  `major_code` VARCHAR(50) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `quota` INT DEFAULT 0,
  `duration` INT DEFAULT 4,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `candidates` (
  `user_id` INT UNSIGNED NOT NULL PRIMARY KEY,
  `cmnd_cccd` VARCHAR(50) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `gender` VARCHAR(20) DEFAULT NULL,
  `high_school` VARCHAR(255) DEFAULT NULL,
  `graduation_year` INT DEFAULT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `applications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `candidate_id` INT UNSIGNED NOT NULL,
  `major_id` INT UNSIGNED NOT NULL,
  `application_code` VARCHAR(100) NOT NULL,
  `method` VARCHAR(50) DEFAULT NULL,
  `subject_scores` TEXT DEFAULT NULL,
  `status` ENUM('pending','approved','rejected','accepted') NOT NULL DEFAULT 'pending',
  `applied_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`candidate_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`major_id`) REFERENCES `majors`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX (`candidate_id`),
  INDEX (`major_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `news` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) DEFAULT NULL,
  `content` TEXT DEFAULT NULL,
  `category` VARCHAR(50) DEFAULT NULL,
  `author_id` INT UNSIGNED DEFAULT NULL,
  `is_published` TINYINT(1) DEFAULT 0,
  `published_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- End of script
