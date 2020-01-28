<?php


include_once('common.php');

    $sql = "select vCountry as Country from country";



    $Country = $obj->MySQLSelect($sql);

       echo json_encode($Country);

?>