<?php
// config/database.php

class Database {
    private static $host = '127.0.0.1';
    private static $db_name = 'db_pidii_ecommerce';
    private static $username = 'root';
    private static $password = '';
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            try {
                // First try connecting to MySQL server directly
                $dsn = "mysql:host=" . self::$host . ";charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                $pdo = new PDO($dsn, self::$username, self::$password, $options);
                
                // Ensure Database exists
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . self::$db_name . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `" . self::$db_name . "`");

                // Check if users table exists, if not run bootstrapper SQL schema
                $tableCheck = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
                if (!$tableCheck) {
                    self::bootstrapDatabase($pdo);
                }

                self::$conn = $pdo;
            } catch (PDOException $e) {
                // Fallback error reporting
                die("<div style='padding:20px; font-family:sans-serif; background:#fee2e2; color:#991b1b;'>
                    <h3>Database Connection Error</h3>
                    <p>" . htmlspecialchars($e->getMessage()) . "</p>
                    <p>Make sure MySQL is running in Laragon / XAMPP.</p>
                </div>");
            }
        }
        return self::$conn;
    }

    private static function bootstrapDatabase($pdo) {
        $sqlFile = __DIR__ . '/../database.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $pdo->exec($sql);
        }
    }
}
