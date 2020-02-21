<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Announcement;
use \App\Models\loan;
use \App\Models\Contribution;
use \App\Models\User;
use \App\Models\Group;
use \App\Models\Borrow;
use \App\Models\Term;
use \App\Flash;

class ControlPanel extends Authenticated
{

    protected $user_info;
    
    protected function before()
    {

        $this->user_info  = User::findByID($_SESSION['user_id']);

        if ($this->user_info['access_rights'] != 9) {
            Flash::addMessage('Unauthorized to access', Flash::WARNING);

                $this->redirect(Auth::getReturnToPage());
        }
    }



    public function indexAction()
    {
        # code...
    
        View::renderTemplate('ControlPanel/index.html', [
                            'user_info'         => $this->user_info
                        ]);
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

    public function termsAction()
    {
        $terms = new Term();

        $terms_list = $terms->view();

        View::renderTemplate('/ControlPanel/term.html',[
            'terms' => $terms_list
        ]);
    }
}
