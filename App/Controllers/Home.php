<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Announcement;
use \App\Models\loan;
use \App\Models\Contribution;

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
        $contri         = Contribution::records($_SESSION['user_id']);
        $loan_records   = Loan::records($_SESSION['user_id']);

        $announcement = Announcement::load();

        View::renderTemplate('Home/index.html',[
            'announcements' => $announcement,
            'contributions' => $contri,
            'loan_records' => $loan_records
        ]);
    }
}
