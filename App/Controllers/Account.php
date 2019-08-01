<?php

namespace App\Controllers;

use \App\Models\User;

/**
 * Account controller
 *
 * PHP version 7.0
 */
class Account extends \Core\Controller
{

    /**
     * Validate if email is available (AJAX) for a new signup.
     *
     * @return void
     */
    public function validateEmailAction()
    {
        $is_valid = ! User::emailExists($_GET['email']);
        
        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }

    public function getIdAction()
    {
        $is_valid = User::searchById($_GET['term']);
        
        
        $return_arr = array();

        foreach ($is_valid as $value) {
            # code...
            $return_arr[] = $value['id']." - ". $value['name']." - ". $value['email'];
        }
        header('Content-Type: application/json');
        echo json_encode($return_arr);
    }
}
