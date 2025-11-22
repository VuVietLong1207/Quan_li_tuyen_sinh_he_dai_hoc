<?php
require_once __DIR__ . '/../functions/db_connection.php';

try {
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'password'");
    $col = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$col) {
        echo "users.password column not found. Make sure the users table exists.\n";
        exit(1);
    }

    // Example Type: varchar(60)
    $type = $col['Type'];
    if (preg_match('/varchar\((\d+)\)/i', $type, $m)) {
        $len = (int)$m[1];
        if ($len >= 255) {
            echo "password column already VARCHAR($len). No action needed.\n";
            exit(0);
        }
        echo "Increasing users.password from VARCHAR($len) to VARCHAR(255)...\n";
        $pdo->exec("ALTER TABLE users MODIFY password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL");
        echo "Done.\n";
        exit(0);
    } else {
        echo "users.password is of type $type. If it's too small, consider altering it to VARCHAR(255).\n";
        exit(0);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(2);
}
