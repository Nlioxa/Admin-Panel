<?php

// table names of the connected database
$tables_names = array_keys($_SESSION['tables']);

// keys of super global "_GET" array
$_get_keys = array_keys($_GET);

// checked table names
$checked_tables = array_intersect($_get_keys, $tables_names);

foreach ($checked_tables as $checked_table) {
    foreach ($_SESSION['tables'] as $table_name => $table_columns) {
        // do if a table is checked 
        if ($checked_table == $table_name) {
            // table field names
            $field_names =  array_keys($table_columns);
            // checked table field names
            $checked_fields = array_intersect($field_names, $_get_keys);
            //
            // compose a database query string
            $query = 'select';
            // add checked fields
            if ($checked_fields)
                // if there are ckecked fields
                foreach ($checked_fields as $field) {
                    $query .= ' `' . $field . '`,';
                }
            else
                // otherwise add "all" symbol
                $query .= ' * ';
            // the last apostrophe is redundant
            $query = substr($query, 0, strlen($query) - 1);
            // add source table
            $query .= ' from ' . $checked_table;
            // add 'where' clause if necessery            
            if (isset($_GET[$table_name . '-id_' . 'filter'])) {
                $where_clause = $_GET[$table_name . '-id_' . 'filter'];
                if (preg_match("/^\d+-{1}\d+$/", $where_clause)) {
                    $where_limits = explode('-', $where_clause);
                    $where_clause = ' where `' . $table_name . '-id` between ' .
                        min($where_limits) . ' and ' . max($where_limits);                    
                }   
                elseif (preg_match("/^(\d+ ?)+$/", $where_clause)) {
                    $where_elements = explode(' ', $where_clause);
                    $where_clause = ' where `' . $table_name . '-id` in ' . '(';
                    foreach ($where_elements as $num) {
                        $where_clause .= $num . ', ';
                    }
                    $where_clause = substr($where_clause, 0, strlen($where_clause) - 2);
                    $where_clause .= ')';
                }                
                // echo '<p>' . $query . $where_clause . '</p>';
                $query .= $where_clause;
            }

            //
            // execute database query
            if (isset($_POST['query'])) {
                $query = $_POST['query'];
            }
            $query_result = mysqli_query($_SESSION['connection'], $query);
            // space for tables maker
            if ($query_result) {
                // convert query result to a convenient form
                $data = mysqli_fetch_all($query_result);
                // get number of selected records
                $data_size = mysqli_num_rows($query_result);
                // get table field names
                $data_fields = mysqli_fetch_fields($query_result); 
                // start composing an html table
                echo '<table>';
                if (!isset($_POST['query'])) {
                    echo '<caption>' . $table_name . '</caption>';
                }
                // echo '<caption>' . $table_name . '</caption>';
                echo '
                <tr>
                    <th class = data_size> Count </th>
                    <td class = data_size colspan = ' . count($data_fields) . '>' . $data_size . '</td>
                </tr>';
                if ($data_size > 0) {                       
                    echo '<tr>';
                    foreach($data_fields as $field) {
                        echo '<th>' . explode('-', $field->name)[1] . '</th>';
                    }
                    echo '</tr>';
                    foreach($data as $row) {
                        echo '<tr>';
                        foreach($row as $key => $value) {
                            echo '<td>' . $value . '</td>';
                        }
                        echo '</tr>';
                    }            
                }    
                echo '</table>';    
            }
            if (isset($_POST['query'])) {
                break 2;
            }
            //
            //
        }        
    }
}
?>