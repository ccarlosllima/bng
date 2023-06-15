<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function check_session()
{
    // check if there is an active session
    session_start();
    return isset($_SESSION['user']);
}

function logger($message = '', $level = 'info')
{
    // create log channel
    $log = new Logger('app_logs');
    $log->pushHandler(new StreamHandler(LOGS_PATH));

    // add log message
    switch ($level) {
        case 'info':
            $log->info($message);
            break;
        case 'notice':
            $log->notice($message);
            break;
        case 'warning':
            $log->warning($message);
            break;
        case 'error':
            $log->error($message);
            break;
        case 'critical':
            $log->critical($message);
            break;
        case 'alert':
            $log->alert($message);
            break;
        case 'emergency':
            $log->emergency($message);
            break;
        default:
            $log->info($message);
            break;
    }
}


// function for print data
function dd($data, $dei = true)
{
    echo '<pre>';
    if (is_object($data) || is_array($data)) {
        print_r($data);
    } else {
        echo $data;
    }

    if ($dei) {
        echo ('<br>FIM</br>');
    }
}