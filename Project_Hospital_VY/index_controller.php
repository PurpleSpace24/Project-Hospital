<?php
/**
 * @var $link
 */

    //call file to connect to the database
    require_once "configs/config.php";

    //array to house all names
    $array = array();

    //sql to get names from table
    $sql_dep = "SELECT dep_name FROM departments";
    $result_dep = $link->query($sql_dep);


    //check if any names exist
    if ($result_dep->num_rows > 0) {
        //go through all names
        while($row = mysqli_fetch_array($result_dep)) {
            //store all data 
            $array[] = $row; 
        }
    }

    //get array size
    $array_size = count($array);
    //get left list num of results to display
    $left_size = ceil($array_size / 2);
    //get right list num of results to display
    $right_size = $array_size - $left_size;

    //split into two arrays
    $array_left = array_slice($array, 0, $left_size);
    $array_right = array_slice($array, $left_size, $array_size);
?>