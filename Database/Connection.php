<?php

namespace Database;

use PDO;
use PDOException; // Make sure this is imported
use Throwable; // Import Throwable for the general catch block

class Connection
{
    const HOST = 'theaterappserverdb.mysql.database.azure.com';
    const DB_NAME = 'theatredb';
    const USERNAME = 'mohamed';
    const PASSWORD = 'momo2025#$'; // Ensure this matches the password in Azure

    protected $connection;

    public function __construct()
    {
        // Add logging to see if constructor is called
        error_log("Connection __construct started.");

        try {
            // Attempt the database connection
            error_log("Attempting PDO connection to host: " . self::HOST . " db: " . self::DB_NAME . " user: " . self::USERNAME); // Log details (exclude password!)

            $this->connection = new PDO(
                'mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME,
                self::USERNAME,
                self::PASSWORD
                // Consider adding SSL/TLS options here if needed and enforced on Azure
                // e.g., , [\PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/BaltimoreCyberTrustRoot.crt.pem', \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true]
                // App Service PHP images might have a default CA bundle, e.g., PDO::MYSQL_ATTR_SSL_CAPATH => '/etc/ssl/certs/'
                // If you have SSL enforcement on Azure, and still get errors, check App Service docs for PHP SSL config
            );

            // Optional: Set error mode if not done elsewhere
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            error_log("Database connection successful!"); // Log success

        } catch (PDOException $e) {
            // Log the specific database connection error message to the App Service Log Stream
            error_log("Database Connection Failed Caught PDOException: " . $e->getMessage());

            // Set connection to null
            $this->connection = null;

            // --- REMOVED: DO NOT re-throw the exception here to prevent the crash ---
            // throw $e; // <-- This line caused the crash if not caught elsewhere
            // ---------------------------------------------------------------------

        } catch (Throwable $e) { // Catch any other unexpected errors during connection setup
             error_log("An unexpected error occurred during DB connection setup: " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
             $this->connection = null;
             // DO NOT re-throw!
        }
        error_log("Connection __construct finished."); // Add logging at the end
    }

    public function getConnection()
    {
         // Optional: Add a check here if the connection failed in the constructor
         if ($this->connection === null) {
             // Depending on your app, you might want to handle this:
             // error_log("Attempted to get a null database connection.");
             // throw new \Exception("Database connection is not available."); // Throw a *different* exception if you need to signal failure downstream
         }
        return $this->connection;
    }

    public function destroy()
    {
        $this->connection = null;
    }

}