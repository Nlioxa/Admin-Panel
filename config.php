<?php

session_start();

$server_name = 'localhost';
$user_name = 'root';
$password = '';
$db_name = 'students';

// try to connect to the 'server_name' server
$connection = mysqli_connect($server_name, $user_name, $password);

// if 'db_name' does not exist create a new one with it's name
$sql_query = 'CREATE DATABASE ' . $db_name;
mysqli_query($connection, $sql_query);

// try to connect to the 'db_name' database
$connection = mysqli_connect($server_name, $user_name, $password, $db_name);

// check if the connection is set correctly 
if (!$connection) {
    die ("Connection failed" . mysqli_connect_error());
}
else {
    // record an object that represents the connection to a MySQL Server ('server_name' server)
    $_SESSION['connection'] = $connection;
}

// an associative array of sql queries: three 'create table' queries
// to prepare the 'db_name' database for any further work
$sql_query = array (
    '
    CREATE TABLE products (
        CONSTRAINT products_pk
            PRIMARY KEY (id),
        id      INT (3) UNSIGNED AUTO_INCREMENT,
        name    VARCHAR (20),
        price   FLOAT (6, 2)
    );',
    '
    CREATE TABLE customers (
        CONSTRAINT customers_pk
            PRIMARY KEY (id),
        id      INT (3) UNSIGNED AUTO_INCREMENT,
        name    VARCHAR (20),
        email   VARCHAR (50)
    );',
    '
    CREATE TABLE orders (
        CONSTRAINT orders_pk
            PRIMARY KEY (id),
        id          INT (3) UNSIGNED AUTO_INCREMENT,                    
        id_customer INT (3) UNSIGNED,
                    CONSTRAINT order_customer_fk FOREIGN KEY (id_customer)
                        REFERENCES  customers (id) ON DELETE CASCADE,
        id_product  INT (3) UNSIGNED,
                    CONSTRAINT order_product_fk FOREIGN KEY (id_product)
                        REFERENCES products (id) ON DELETE CASCADE,
        price       FLOAT (6, 2)
    );
    '
);

// execute every sql query from the associative array you have made recently
foreach ($sql_query as $query) {
    $sql_query_result = mysqli_query($connection, $query);
    if (!$sql_query_result) {
        // echo '<p>Error creating table:</p>' . mysqli_error($connection);
    }
}
?>