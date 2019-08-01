<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Loan;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Loans extends Authenticated
{

    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */
    /*
    protected function before()
    {
        $this->requireLogin();
    }
    */

    /**
     * Items index
     *
     * @return void
     */
    public function indexAction()
    {
        $user_loans = Loan::viewUserLoan($_SESSION['user_id']);
        View::renderTemplate('Loans/index.html',[
            'loans' => $user_loans
        ]);
    }

    
    /**
     * Add a new item
     *
     * @return void
     */
    public function newAction()
    {
        echo "new action";
    }

    /**
     * Show an item
     *
     * @return void
     */
    public function showAction()
    {
        echo "show action";
    }
    
    public function viewAction()
    {
        $loan_info      = Loan::view($this->route_params['id']);
        $loan_records   = Loan::records($this->route_params['id']);
        
        View::renderTemplate('loans/view.html', [
            'loan_info'            => $loan_info,
            'loan_records'    => $loan_records
        ]);
            
            //print_r($this->route_params);
            //echo $this->route_params['id'];
    }
}
