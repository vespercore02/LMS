<?php

namespace App\Controllers\Admin;

use \Core\View;
use \App\Models\User;
use \App\Models\Group;
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


        array_push(
            $_POST,
            $_POST['interestRate'],
            $_POST['monthlyinterest'],
            $_POST['overallmonthlyinterest'],
            $_POST['totalLoan'],
            $_POST['monthlyPayment']
        );

        //print_r($_POST);
        
        $loan = new Loan($_POST);

        if ($loan->save()) {
            $this->redirect('/signup/success');
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
            
    }

    public function updateAction()
    {
        if ($_POST) {
            if (Loan::lastRecord($_POST['loan_id'])) {
                # code...
                $loan_info      = Loan::lastRecord($_POST['loan_id']);
                
                $_POST['amount_left'] = ($loan_info['amount_left'] - $_POST['amount_paid']);
                $_POST['month_left'] = $loan_info['month_left'] - 1;

                array_push($_POST, $_POST['amount_left'], $_POST['month_left']);
                print_r($_POST);
            } else {
                $loan_info      = Loan::view($_POST['loan_id']);
                
                $_POST['amount_left'] = ($loan_info['totalLoan'] - $_POST['amount_paid']);
                $_POST['month_left'] = $loan_info['loan_payment_months'] - 1;

                array_push($_POST, $_POST['amount_left'], $_POST['month_left']);
                print_r($_POST);
            }
            
            $loan = new Loan($_POST);
            if ($loan->update()) {
                $this->redirect('/signup/success');
            }
        } else {
            $loan_info      = Loan::view($this->route_params['id']);
            $loan_records   = Loan::records($this->route_params['id']);
        
            View::renderTemplate('Admin/update.html', [
            'loan_info'            => $loan_info,
            'loan_records'    => $loan_records
        ]);
            
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

                $this->redirect('/admin/users/index');
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
        if (isset($this->route_params['id'])) {
            # code...
            $info           = User::searchById($this->route_params['id']);
            $contri         = Contribution::records($this->route_params['id']);
            $loan_records   = Loan::records($this->route_params['id']);

            View::renderTemplate('Admin/member-info.html', [
                'info' => $info,
                'contri_records' => $contri,
                'loan_records' => $loan_records
            ]);

        } else {
            $members = User::getMembers();
        
            $groups = Group::getGroups();

            $accesses = User::getAccessRights();

            View::renderTemplate('Admin/members.html', [
            'members' => $members,
            'groups' => $groups,
            'accesses' => $accesses
        ]);

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
            $_POST['password']      = "Default@01";

            $member = new User($_POST);

            if ($member->save()) {

                $link = $member->returnActivationLink();
                Flash::addMessage('Member successful added please access this link '.$link);

                $this->redirect('/admin/users/members');
            } else {
                View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);
            }
        }
    }

    public function editmemberAction()
    {

        if ($_POST) {
            # code...

            print_r($_POST);

            $edit_member = new User($_POST);
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

                $this->redirect('/admin/users/access');
            } else {
                Flash::addMessage('Access Rights Failed added', WARNING);

                $this->redirect('/admin/users/access');
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
        $groups = Group::getGroups();

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

                Group::groupAddInfo($groups->saveGroups());

                Flash::addMessage('Group successful added');

                $this->redirect('/admin/users/groups');
            } else {
                Flash::addMessage('Group Failed added', WARNING);

                $this->redirect('/admin/users/groups');
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

            $newDate = date("Md", strtotime($_POST['contri_date']));
            $_POST['column_name'] = strtolower($newDate);

            $groups = new Contribution($_POST);
            /*
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            
            $newDate = date("d-M", strtotime($_POST['contri_date']));
            echo strtolower($newDate);
            */
            //echo $groups->checkTermRecord();

            echo $groups->checkTermRecord();
            
            if ($groups->save()) {
                # code...

                $groups->saveTermRecord();

                //echo $_POST['contri_date'];
                
                Flash::addMessage('Contribution is successful added');

                $this->redirect('/admin/users/members/'.$_POST['user_id']);
                
            } else {
                
                Flash::addMessage('Contribution is Failed to added', 'warning');

                $this->redirect('/admin/users/members/'.$_POST['user_id']);
                
            }
            
        }
    }
}
