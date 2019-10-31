<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Group;
use \App\Models\Borrow;
use \App\Models\Loan;
use \App\Models\Announcement;
use \App\Models\Contribution;
use \App\Models\Summary;
use \App\Auth;
use \App\Flash;


class Admin extends \App\Controllers\Authenticated
{
    public function contributionMonth()
    {
        if (isset($_GET['month'])) {
            # code...

            $contributions          = Contribution::getMonthContriRecords($_GET['month']);

            echo json_encode($contributions);
        }
    }

    public function summaryMonth()
    {
        if (isset($_GET['month'])) {
            # code...

            $summary          = Summary::getMonthSummaryRecords($_GET['month']);

            echo json_encode($summary);
        }
    }
}
