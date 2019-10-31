<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Announcement;
use \App\Models\loan;
use \App\Models\Contribution;
use \App\Models\User;
use \App\Models\Group;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        if (isset($_SESSION['user_id'])) {
            # code...
            $user_info  = User::findByID($_SESSION['user_id']);

            switch ($user_info['access_rights']) {
                case '0':
                    # code...

                    View::renderTemplate('Home/index-borrower.html');

                    break;

                case '1':
                    # code...

                    View::renderTemplate('Home/index-member.html');

                    break;

                case '2':
                    # code...

                    $group_info     = Group::getGroupInfo($user_info['belonging_group']);

                    View::renderTemplate('Home/index-groupleader.html', [
                        'group_info' => $group_info
                    ]);

                    break;

                case '9':
                    # code...

                    View::renderTemplate('Home/index-admin.html');

                    break;

                default:
                    # code...
                    break;
            }
        } else {
            # code...
            View::renderTemplate('Login/new.html');
        }
    }

    public function groupmonthdetails()
    {
        if (isset($_GET['month']) and isset($_GET['group'])) {
            # code...
            
            $contri =  Contribution::getGroupMonthlyContri($_GET['month'], $_GET['group']);

            echo json_encode($contri);

            //print_r($contri);
        }
    }
}
