<?php
// get table names from database
$tables = mysqli_fetch_all(
    mysqli_query($_SESSION['connection'], 
        'show tables')
);
// get tables' columns info: fields' names and their data types
foreach ($tables as $table) {
    foreach ($table as $table_name) {
        // database query for columns info
        $query = 'show columns from ' . $table_name;
        // database query execution
        $result = mysqli_query($_SESSION['connection'], $query);        
        // set container for data table info
        $table_fields = [];
        // cast database data types to php data types
        foreach ($result as $row) {
            $field_type = $row['Type'];
            if (strpos($field_type, 'int') !== false)
                $row['Type'] = gettype(1);
            elseif (strpos($field_type, 'varchar') !== false)
                $row['Type'] = gettype('1');
            elseif (strpos($field_type, 'float') !== false)
                $row['Type'] = gettype(1.0);

            $table_fields = array_merge($table_fields, [$row['Field'] => $row['Type']]);
        }
        // set an array 'tables' in the super global array '$_SESSION'
        if (!isset($_SESSION['tables']))
            $_SESSION['tables'] = [];
        // append '$_SESSION' with new data via merge
        $_SESSION['tables'] = array_merge($_SESSION['tables'], [$table_name => $table_fields]);
    }
}
?>