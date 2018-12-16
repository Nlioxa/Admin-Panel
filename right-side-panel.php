<?php

$actions = [
    'Delete',
    'Edit',
    'Insert'
];

echo '<fieldset>';
echo '<select name="action">';
foreach ($actions as $action) {
    if ($action == $_POST['action']) $selected = 'selected';
    else $selected = '';
    echo '<option value="' . $action . '" ' . $selected . '>' . $action . '</option>';
}
echo '</select><br>';
echo '<legend for="action">Action</legend>';
echo '</fieldset>';

// set default value for the "select table" field
if (!isset($_POST['table_name'])) {
    $_POST['table_name'] = array_keys($_SESSION['tables'])[0];
}

// compose right-side panel
if (isset($_POST['action'])) {
    // define a "select table" field  
    $table_names = array_keys($_SESSION['tables']);
    echo '<fieldset>';      
    echo '<label for="table_name">Table</legend><br>'; 
    echo '<select name="table_name">';
    foreach ($table_names as $table_name) {
        if ($table_name == $_POST['table_name']) $selected = 'selected';
        else $selected = '';
        echo '<option value="' . $table_name . '" ' . $selected . '>' . $table_name . '</option>';
    }
    echo '</select><br>';

    //
    // compose form here
    switch($_POST['action']) {
        // "delete" form
        case $actions[0]:
            echo '<label for="id">Id<label><br>';
            echo '<input type="number" name="id"/>';
            if (isset($_POST['id']) and isset($_POST['table_name'])) {
                $query = 'delete from ' . $_POST['table_name'] . ' where 
                    `' . $_POST['table_name'] . '-id` = ' . $_POST['id'];
                mysqli_query($_SESSION['connection'], $query);
            }
            break;
        // "edit" form
        case $actions[1]:
            if (isset($_POST['table_name'])) {
                $table_name = $_POST['table_name'];
                $field_names = $_SESSION['tables'][$table_name];
                $query = 'update ' . $table_name . ' set ';
                foreach ($field_names as $field_name => $field_type) {
                    switch($field_type) {
                        case 'integer':
                            $pattern = 'pattern="^\d{1,3}$"';
                            break;
                        case 'double':
                            $pattern = 'pattern="^(\d{0,6})|(\d{0,6}\.\d{1,2})$"';
                            break;
                        case 'string':
                            $pattern = 'pattern="^\X*$"';
                            break;
                    }
                    $short_name = explode('-', $field_name)[1];
                    // if ($short_name == 'id') $pattern .= ' required';
                    if (isset($_POST[$field_name])) $placeholder = $_POST[$field_name];
                    else $placeholder = $field_type;
                    echo '<label for="' . $field_name . '">' . $short_name . '</label><br>';
                    echo '<input type="text" name="' . $field_name . '" ' . $pattern . ' placeholder="' . $placeholder . '"><br>';

                    if (isset($_POST[$field_name])) {
                        if ($_POST[$field_name] !== '')
                            $query .= '`' . $field_name . '`="' . $_POST[$field_name] . '",';
                    }
                }
                $query = substr($query, 0, strlen($query) - 1) . ' ';
                if (isset($_POST[$table_name . '-id'])) {
                    $query .= 'where `' . $table_name . '-id`="' . $_POST[$table_name . '-id'] . '"';                
                    mysqli_query($_SESSION['connection'], $query);
                }                
            }
            break;
        // "insert" form
        case $actions[2]:
            if (isset($_POST['table_name'])) {
                $table_name = $_POST['table_name'];
                $field_names = $_SESSION['tables'][$table_name];
                $query = 'insert into ' . $table_name . ' (';
                foreach (array_keys($field_names) as $field_name) {
                    if ($field_name != $table_name . '-id') {
                        $query .= '`' . $field_name . '`,';
                        // echo $field_name . ' ' . $table_name . '-id<br>';
                    }
                }                
                $query = substr($query, 0, strlen($query) - 1) . ')';

                $query .= ' values (';
                foreach ($field_names as $field_name => $field_type) {
                    switch($field_type) {
                        case 'integer':
                            $pattern = 'pattern="^\d{1,3}$"';
                            break;
                        case 'double':
                            $pattern = 'pattern="^(\d{0,6})|(\d{0,6}\.\d{1,2})$"';
                            break;
                        case 'string':
                            $pattern = 'pattern="^\X*$"';
                            break;
                    }
                    $short_name = explode('-', $field_name)[1];
                    if ($short_name != 'id') {
                        if (isset($_POST[$field_name])) $placeholder = $_POST[$field_name];
                        else $placeholder = $field_type;
                        echo '<label for="' . $field_name . '">' . $short_name . '</label><br>';
                        echo '<input type="text" name="' . $field_name . '" ' . $pattern . ' placeholder="' . $placeholder . '"><br>';

                        if (isset($_POST[$field_name])) {
                            $data = $_POST[$field_name];
                            if ($data != '')
                                $is_insertable = true;
                            else $is_insertable = false;
                        }
                        else {
                            $data = '';
                            if (!isset($is_insertable)) $is_insertable = false;
                        }
                        $query .= '"' . $data . '",';
                    }
                }
                $query = substr($query, 0, strlen($query) - 1) . ')';                
                        
                // echo $query;
                if ($is_insertable) {             
                    if (!mysqli_query($_SESSION['connection'], $query)) {
                        echo mysqli_error($_SESSION['connection']) . '<br>';
                        echo $query;
                    }
                }     
                // echo $is_insertable;           
            }
            break;
    }
    
    
    //
    //

    echo '</fieldset>';
    
}

echo '<button type="submit">Submit</button>';
echo '<button type="reset">Reset</button>';

?>