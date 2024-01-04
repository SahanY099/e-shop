<?php

class Database
{
    public static $connection;

    public static function setUpConnection()
    {
        if (!isset(Database::$connection)) {
            // put you database credentials here
            Database::$connection = new mysqli("hostname", "username", "password", "db-name");
        }
    }

    public static function iud($query)
    {
        Database::setUpConnection();
        Database::$connection->query($query);
    }

    public static function search($query)
    {
        Database::setUpConnection();
        return Database::$connection->query($query);
    }
}
