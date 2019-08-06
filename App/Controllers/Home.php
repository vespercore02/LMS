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
            $contri_records         = Contribution::records($_SESSION['user_id']);
            $loan_records           = Loan::records($_SESSION['user_id']);
            $loan_info              = Loan::info($_SESSION['user_id']);
            $rights                 = User::findByID($_SESSION['user_id']);
            $group_members          = User::getGroupMembers($rights['belonging_group'], $_SESSION['user_id']);
            $group_members_contri   = Contribution::getGroupMembersContri($group_members);
            $members_count = count($group_members);

            for ($i=0; $i < $members_count; $i++) { 
                # code...
                echo $group_members[$i]['id']."<br>";
            }

            $announcement = Announcement::load();

            View::renderTemplate('Home/index.html',[
                'announcements' => $announcement,
                'contri_records'=> $contri_records,
                'loan_records'  => $loan_records,
                'loan_info'     => $loan_info,
                'rights'        => $rights,
                'group_members' => $group_members
            ]);

            //print_r($group_members);
            //echo $rights['access_rights'];
        }else {
            # code...
            View::renderTemplate('Home/index.html');
        }
        
    }
}
