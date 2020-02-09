<?php

namespace App\Controllers;

use \App\Models\User;
use \Core\View;
use \App\Auth;
use \App\Flash;

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

    public function getId()
    {
        $is_valid = User::searchById($_GET['term']);
        
        
        $return_arr = array();

        foreach ($is_valid as $value) {
            # code...
            $return_arr[] = $value['id']." - ". $value['name']." - ". $value['email']." - ".$value['belonging_group'] ;
        }
        header('Content-Type: application/json');
        echo json_encode($return_arr);
    }

    public function managesAction()
    {
        $user_info  = User::findByID($_SESSION['user_id']);

        //print_r($_SESSION);

        View::renderTemplate('Profile/index.html', [
                        'user_info'         => $user_info
                    ]);
    }

    public function updatepass()
    {
        $user_info  = User::findByID($_SESSION['user_id']);

        $user = User::authenticate($user_info['email'], $_POST['old_password']);

        if ($user) {

            if ($_POST['new_password'] != $_POST['confirm_new_password']) {
                # code...
    
                Flash::addMessage('Password update unsuccessful, your new password did match to confirm new password', Flash::WARNING);

                $this->redirect(Auth::getReturnToPage());
            } else {
                # code...
                $_POST['user_id'] = $user_info['id'];

                $update_pass = new User($_POST);

                $update_pass->updatepass();

                Flash::addMessage('Password update successful, please log out and log in again to try your new password');

                $this->redirect(Auth::getReturnToPage());

            }
        } else {
            # code...

            Flash::addMessage('Password update unsuccessful, your old password is incorrect', Flash::WARNING);
            $this->redirect(Auth::getReturnToPage());
        }
    }
}
