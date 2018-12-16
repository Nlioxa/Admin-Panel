<!DOCTYPE html>

<!-- create a connection to database -->
<?php
    // make a connection to database
    require 'config.php';
    // get database tables' data
    require 'get-tables-info.php';   
?>

<html>
<head>
    <title>Lab5</title>
    <link rel='stylesheet' type='text/css' href='styles.css'>
</head>
<body>
    <header>         
        <!-- <form action='' method='get'>
            <input type='search' id='search' name='query' placeholder='Select' />
        </form>     -->
    </header>    
    <!-- Left-side panel -->
    <aside class='panel' id='left'>        
        <!-- Panel Title -->
        <p>Filter</p>
        <!-- Panel Body -->
        <form action ='' method='get'>
            <?php require 'left-side-panel.php' ?>
        </form>

         <!--select form -->
         <!-- <form action='' method='post'>
            <legend>Select</legend>

            <label for='select-cust-name'>Customer Name<label>
            <input type='text' name='select-cust-name' required/><br>

            <label for='select-prod-name'>Product Name<label>
            <input type='text' name='select-prod-name' required/><br>

            <label for='select-price-value'>Price Value<label>
            <input type='text' name='select-price-value' required/><br>

            <button type="submit">Confirm</button>
         </form> -->
         <?php 
            // if (isset($_POST['select-cust-name'])) {
            //     $_POST['query'] =   'SELECT `customers`.`customers-name`, `products`.`products-name`, `products`.`products-price` 
            //     FROM `customers` INNER JOIN `products` on `products`.`products-id` = `customers`.`customers-id` 
            //     WHERE `customers`.`customers-name`="' . $_POST['select-cust-name'] . '" 
            //     AND `products`.`products-price` BETWEEN 0 AND ' . $_POST['select-price-value'] . ' 
            //     AND `products`.`products-name` = "' . $_POST['select-prod-name'] . '"';
            //     // echo $_POST['query'];
            // }
         ?>
    </aside>
    <!-- Right-side panel -->
    <aside class='panel' id='right'>
        <!-- Panel Title -->
        <p>Modify</p>
        <!-- Panel Body -->
        <form action ='' method='post'>
            <?php require 'right-side-panel.php' ?>
        </form> 
    </aside>
    <main>        
        <?php require 'data-view.php' ?>
    </main>
</body>
</html>