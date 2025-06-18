<?php

namespace Database;

use PDO;
use PDOException; // Make sure this is imported

class Connection
{
    const HOST = 'theaterappserverdb.mysql.database.azure.com';
    const DB_NAME = 'theatredb';
    const USERNAME = 'mohamed';
    const PASSWORD = 'momo2025#$';

    protected $connection;

    public function __construct()
    {
        try {
            // Attempt the database connection
            $this->connection = new PDO(
                'mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME,
                self::USERNAME,
                self::PASSWORD
                // Consider adding SSL/TLS options here if needed and enforced on Azure
                // e.g., , [\PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/BaltimoreCyberTrustRoot.crt.pem', \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true]
                // Azure App Service *might* have the CA bundle pre-installed somewhere accessible like /etc/ssl/certs/ca-certificates.crt
                // Check Azure PHP documentation for recommended SSL path in App Service
            );

            // Optional: Set error mode if not done elsewhere
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            // Log the specific database connection error message to the App Service Log Stream
            error_log("Database Connection Failed: " . $e->getMessage());

            // Instead of letting it crash, maybe set connection to null
            $this->connection = null;

            // Depending on your application structure, you might want to
            // - throw the exception again for a higher-level handler
            // - return false or a specific error object
            // - display a user-friendly error page if this is part of initial page load

            // For now, just logging and setting connection to null is a good start
            // to prevent the fatal crash. The application might still fail later
            // if it tries to use the null connection.
            // You could also add die("Database unavailable"); for debugging if this __construct
            // is called on every request.

             // Re-throwing for debugging might be useful to see where it's caught (or not)
             throw $e; // Re-throw the exception
        }
    }

    public function getConnection()
    {
        // Consider adding a check here if $this->connection is null
        // if ($this->connection === null) {
        //    // Handle case where connection failed in constructor
        //    // e.g., throw a custom exception or return false
        // }
        return $this->connection;
    }

    // destroy method is fine
    public function destroy()
    {
        $this->connection = null;
    }
}

