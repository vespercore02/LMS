<?php 

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Group;
use \App\Models\Borrow;
use \App\Models\Loan;
use \App\Models\Term;
use \App\Models\Announcement;
use \App\Models\Contribution;
use \App\Auth;
use \App\Flash;

class Members extends Authenticated
{

    public function indexAction()
    {

        if (isset($this->route_params['id'])) {
            # code...
            $id = $this->route_params['id'];
        }else {
            # code...
            $id = 1;
        }

        //$member_list  = User::getMembers();
        $member_list  = User::getMembersRange($id);
        $member_count  = User::getMemberCount();

        View::renderTemplate('/Member/index.html',[
            'id' => $id,
            'member_list' => $member_list,
            'member_count' => $member_count
        ]);
    }

    public function viewAction()
    {
        if (isset($this->route_params['id']))
        {
            $term = new Term();
            $terms             = $term->view();
            $user_info         = User::findByID($this->route_params['id']);
            $contribution_list = Contribution::viewMember($this->route_params['id']);
            $borrow_list       = Borrow::viewMember($this->route_params['id']);
            /*
            echo "<pre>";
            print_r($borrow_list);
            echo "</pre>";
            */
            View::renderTemplate('/Member/view.html',[
                'terms'             => $terms,
                'user_info'         => $user_info,
                'contribution_list' => $contribution_list,
                'borrow_list'       => $borrow_list
            ]);
        }
    }
}



?>