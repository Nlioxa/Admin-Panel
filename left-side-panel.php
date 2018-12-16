<?php

$tables_info = $_SESSION['tables'];

if (!isset($_SESSION['tables'])) {
    die();
}

$tables_names = array_keys($tables_info);

echo '<fieldset>';
echo '<legend>Tables</legend>';

foreach ($tables_names as $table_name) {    
    if (isset($_GET[$table_name]))
        $checked = 'checked';
    else 
        $checked = '';
    echo '<input type="checkbox" name="'. $table_name . '" ' . $checked . ' />';
    echo '<label for="' . $table_name . '">' . $table_name . '</label><br>';
    if ($checked === 'checked') {
        echo '<fieldset>';
        foreach ($tables_info[$table_name] as $field => $type) {
            if ($field === $table_name . '-id') {
                $filter_id_name = $field . '_' . 'filter';
                if (isset($_GET[$filter_id_name])) {
                    $filter_id_value = $_GET[$filter_id_name];
                }
                else {
                    $filter_id_value = '';
                }
                echo '<input type="text" name="' . $filter_id_name . '" value="' . $filter_id_value . '" placeholder = "all" 
                    pattern="^(\d+-{1}\d+|(\d+ ?)+|)$" /><br>';
            }
            $var_name = $field;
            if (isset($_GET[$var_name]))
                $checked = 'checked';
            else {
                $checked = '';
            }
            echo '<input type="checkbox" name="'. $var_name . '" ' . $checked . ' />';
            echo '<label for="' . $var_name . '">' . explode('-', $field)[1] . '</label><br>';
        }
        echo '</fieldset>';
    }
}

// echo 

echo '</fieldset>';

echo '<button type="submit">Update</button>';

?>