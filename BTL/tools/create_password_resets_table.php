<?php
require_once __DIR__ . '/../functions/db_connection.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS `password_resets` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `user_id` INT NOT NULL,
      `token` VARCHAR(128) NOT NULL,
      `expires_at` DATETIME NOT NULL,
      `created_at` DATETIME NOT NULL,
      INDEX (`token`),
      INDEX (`user_id`),
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql);
    echo "password_resets table created or already exists.\n";
} catch (PDOException $e) {
    echo "Error creating password_resets table: " . $e->getMessage();
}
