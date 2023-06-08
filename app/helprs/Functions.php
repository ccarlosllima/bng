<?php

function check_session()
{
    // check if there is an active session
    return isset($_SESSION['user']);
}



// function for print data
function printData($data, $dei = true)
{   
    echo '<pre>';
    if (is_object($data) || is_array($data)) {
        print_r($data);
    }else {
        echo $data;
    }
    
    if ($dei) {
        echo('<br>FIM</br>');
    }
}