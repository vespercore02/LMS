<?php

namespace App\Controllers\Admin;

use \Core\View;
use \App\Models\User;
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
class Users extends \App\Controllers\Authenticated
{

    /**
     * Before filter
     *
     * @return void
     */
    protected function after()
    {
        // Make sure an admin user is logged in for example
        // return false;
        //var_dump(Auth::getUser());

        //print_r(Auth::getUser());
        
        $rights = Auth::getUser();
        
        if ($rights) {
            foreach ($rights as $key => $value) {
                # code...
                if ($key == "access_rights") {
                    if ($value != 9) {
                        Flash::addMessage('For admin access only', Flash::WARNING);
    
                        $this->redirect(Auth::getReturnToPage());
                    }
                }
            }
        }
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $loans = Loan::getAll();

        View::renderTemplate('Admin/index.html', [
            'loans' => $loans
        ]);
    }

    /**
     * Admin loan form index
     *
     * @return void
     */
    public function loanformAction()
    {
        View::renderTemplate('Admin/loan-form.html');
    }

    /**
     * Admin loan form create
     *
     * @return void
     */
    public function createAction()
    {
        $_POST['interestRate']             = .05;
        $_POST['monthlyinterest']          = $_POST['loan_amount'] * $_POST['interestRate'];
        $_POST['overallmonthlyinterest']   = $_POST['monthlyinterest'] * $_POST['months_to_pay'];
        $_POST['totalLoan']                = $_POST['overallmonthlyinterest'] + $_POST['loan_amount'];
        $_POST['monthlyPayment']           = $_POST['totalLoan'] / $_POST['months_to_pay'];

        //echo $monthlyinterest."<br>".$overallmonthlyinterest."<br>".$totalLoan."<br>".$monthlyPayment;

        array_push(
            $_POST,
            $_POST['interestRate'],
            $_POST['monthlyinterest'],
            $_POST['overallmonthlyinterest'],
            $_POST['totalLoan'],
            $_POST['monthlyPayment']
        );

        print_r($_POST);
        
        $loan = new Loan($_POST);

        if ($loan->save()) {
            $this->redirect('/loan/public/signup/success');
        } else {
            View::renderTemplate('Admin/form.html', [
                'loan' => $loan
            ]);
        }
    }

    /**
     * Admin loan form create
     *
     * @return void
     */
    public function viewAction()
    {
        $loan_info      = Loan::view($this->route_params['id']);
        $loan_records   = Loan::records($this->route_params['id']);
        
        View::renderTemplate('Admin/view.html', [
            'loan_info'            => $loan_info,
            'loan_records'    => $loan_records
        ]);
            
        //print_r($this->route_params);
            //echo $this->route_params['id'];
    }

    public function updateAction()
    {
        if ($_POST) {
            if (Loan::lastRecord($_POST['loan_id'])) {
                # code...
                $loan_info      = Loan::lastRecord($_POST['loan_id']);
                /*
                echo $loan_info['amount_left']."<br>";
                echo $loan_info['month_left']."<br>";

                echo $_POST['amount_paid']."<br>";
                echo $_POST['month_paid']."<br>";
                */
                $_POST['amount_left'] = ($loan_info['amount_left'] - $_POST['amount_paid']);
                $_POST['month_left'] = $loan_info['month_left'] - 1;

                array_push($_POST, $_POST['amount_left'], $_POST['month_left']);
                print_r($_POST);
            } else {
                $loan_info      = Loan::view($_POST['loan_id']);
                /*
                echo $loan_info['loan_amount']."<br>";
                echo $loan_info['loan_payment_months']."<br>";

                echo $_POST['amount_paid']."<br>";
                echo $_POST['month_paid']."<br>";
                */
                $_POST['amount_left'] = ($loan_info['totalLoan'] - $_POST['amount_paid']);
                $_POST['month_left'] = $loan_info['loan_payment_months'] - 1;

                array_push($_POST, $_POST['amount_left'], $_POST['month_left']);
                print_r($_POST);
            }
            
            $loan = new Loan($_POST);
            if ($loan->update()) {
                $this->redirect('/loan/public/signup/success');
            }
        } else {
            $loan_info      = Loan::view($this->route_params['id']);
            $loan_records   = Loan::records($this->route_params['id']);
        
            View::renderTemplate('Admin/update.html', [
            'loan_info'            => $loan_info,
            'loan_records'    => $loan_records
        ]);
            
            //print_r($this->route_params);
            //echo $this->route_params['id'];
        }
    }

    /**
     * Admin Announcement form index
     *
     * @return void
     */
    public function announcementformAction()
    {
        View::renderTemplate('Admin/announcement-form.html');
    }

    /**
     * Admin Announcement Creation
     *
     * @return void
     */
    public function createannouncementAction()
    {
        if (isset($_POST)) {
            # code...

            $announcement = new Announcement($_POST);

            if ($announcement->save()) {
                # code...

                Flash::addMessage('Announce successful');

                $this->redirect('/loan/public/admin/users/index');
            } else {
                View::renderTemplate('Admin/announcement-form.html', [
                    'announcement' => $announcement
                ]);
            }
        }
    }

    /**
     * Admin Members form index
     *
     * @return void
     */
    public function membersAction()
    {
        if ($this->route_params['id']) {
            # code...
            $info = User::searchById($this->route_params['id']);

            View::renderTemplate('Admin/member-info.html', [
                'info' => $info
            ]);

        } else {
            $members = User::getMembers();
        
            $groups = User::getGroups();

            $accesses = User::getAccessRights();

            View::renderTemplate('Admin/members.html', [
            'members' => $members,
            'groups' => $groups,
            'accesses' => $accesses
        ]);

            //print_r($members);
        }
    }

    /**
     * Admin add member form index
     *
     * @return void
     */
    public function addmemberAction()
    {
        if ($_POST) {
            # code...

            $_POST['name']          = $_POST['member_firstname']." ".$_POST['member_lastname'];
            $_POST['email']         = strtolower($_POST['member_firstname']."@".$_POST['member_lastname'].".com");
            $_POST['password']      = "Defaulat@01";

            $member = new User($_POST);

            if ($member->save()) {
                Flash::addMessage('Member successful added');

                $this->redirect('/loan/public/admin/users/members');
            } else {
                View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);
            }
        }
    }

    /**
     * Admin Access form index
     *
     * @return void
     */
    public function accessAction()
    {
        $accesses = User::getAccessRights();

        View::renderTemplate('Admin/access.html', [
            'accesses' => $accesses
        ]);

        //print_r($accesses);
    }

    /**
     * Admin Add Access form index
     *
     */
    public function addaccessAction()
    {
        if ($_POST) {
            # code...

            $access_rights = new User($_POST);

            if ($access_rights->saveAccessRights()) {
                # code...

                Flash::addMessage('Access Rights successful added');

                $this->redirect('/loan/public/admin/users/access');
            } else {
                Flash::addMessage('Access Rights Failed added', WARNING);

                $this->redirect('/loan/public/admin/users/access');
            }
        }
    }

    /**
     * Admin Group form index
     *
     * @return void
     */
    public function groupsAction()
    {
        $groups = User::getGroups();

        View::renderTemplate('Admin/groups.html', [
            'groups' => $groups
        ]);

        //print_r($groups);
    }

    /**
     * Admin Add Access form index
     *
     */
    public function addgroupsAction()
    {
        if ($_POST) {
            # code...

            $groups = new User($_POST);

            if ($groups->saveGroups()) {
                # code...

                Flash::addMessage('Access Rights successful added');

                $this->redirect('/loan/public/admin/users/groups');
            } else {
                Flash::addMessage('Access Rights Failed added', WARNING);

                $this->redirect('/loan/public/admin/users/groups');
            }
        }
    }

    /**
     * Admin add members Contribution
     *
     */
    public function addcontributionAction()
    {
        if ($_POST) {
            # code...

            $groups = new Contribution($_POST);

            if ($groups->save()) {
                # code...

                echo $_POST['contri_date'];

                Flash::addMessage('Contribution is successful added');

                $this->redirect('/loan/public/admin/users/members/'.$_POST['user_id']);
            } else {
                Flash::addMessage('Contribution is Failed to added', WARNING);

                $this->redirect('/loan/public/admin/users/members/'.$_POST['user_id']);
            }
        }
    }
}
