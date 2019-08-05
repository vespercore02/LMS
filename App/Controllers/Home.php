<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Announcement;
use \App\Models\loan;
use \App\Models\Contribution;
use \App\Models\User;

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
            $contri_records = Contribution::records($_SESSION['user_id']);
            $loan_records   = Loan::records($_SESSION['user_id']);
            $loan_info      = Loan::info($_SESSION['user_id']);
            $rights         = User::findByID($_SESSION['user_id']);

            $announcement = Announcement::load();

            View::renderTemplate('Home/index.html',[
                'announcements' => $announcement,
                'contri_records'=> $contri_records,
                'loan_records'  => $loan_records,
                'loan_info'     => $loan_info
            ]);

                print_r($rights);
            echo $rights['access_rights'];
        }else {
            # code...
            View::renderTemplate('Home/index.html');
        }
        
    }
}
