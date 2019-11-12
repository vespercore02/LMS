<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Group;
use \App\Models\Borrow;
use \App\Models\Loan;
use \App\Models\Announcement;
use \App\Models\Contribution;
use \App\Auth;
use \App\Flash;

/**
 * User admin controller
 *
 * PHP version 5.4
 */
class Groupleader extends \App\Controllers\Authenticated
{
    public function members()
    {
        if (isset($_GET['group'])) {
            # code...

            $group_members          = Group::groupMembers($_GET['group']);

            echo json_encode($group_members);
        }
    }

    public function contributions()
    {
        if (isset($_GET['group'])) {
            # code...

            $group_contributions          = Group::groupContributions($_GET['group']);

            echo json_encode($group_contributions);
        }
    }

    public function contributionMonth()
    {
        if (isset($_GET['group']) and isset($_GET['month'])) {
            # code...

            $group_contributions          = Group::groupContributionsMonth($_GET['group'], $_GET['month']);

            echo json_encode($group_contributions);
        }
    }

    public function addcontribution()
    {
        $add_contri     = new Group($_POST);
        $update_summary = new Contribution($_POST);

        if ($add_contri->groupAddContribution()) {
            # code...

            $add_contri->summaryUpdateContri();

            $update_summary->updateSummaryContri();

            $add_contri->groupUpdateInfo();

            Flash::addMessage('Contribution is successful added');

            $this->redirect('/');

        }else {
            # code...
            Flash::addMessage('Contribution for this date '.$_POST['month'].' and this person '.$_POST['name'].' is already set', "warning");

            $this->redirect('/');
        }
        

        
    }

    public function summaryMonth()
    {
        if (isset($_GET['group']) and isset($_GET['month'])) {
            # code...

            $group_summary          = Group::groupSummaryMonth($_GET['group'], $_GET['month']);

            echo json_encode($group_summary);
        }
    }

    public function borrowMonth()
    {
        if (isset($_GET['group']) and isset($_GET['month'])) {
            # code...

            $group_borrow         = Group::groupBorrowMonth($_GET['group'], $_GET['month']);

            echo json_encode($group_borrow);

        }
    }
}
