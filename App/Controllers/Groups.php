<?php

namespace App\Controllers;

use \App\Models\User;
use \Core\View;
use \App\Auth;
use \App\Models\Announcement;
use \App\Models\loan;
use \App\Models\Contribution;
use \App\Models\Borrow;
use \App\Models\Group;

class Groups extends Authenticated
{
    
    public function before()
    {
        $user_info  = User::findByID($_SESSION['user_id']);
        
        if ($user_info['access_rights'] == 2) {
            # code...

        } else {
            # code...

            Flash::addMessage('Not Allowed to Access the said URL', Flash::WARNING);

            $this->redirect(Auth::getReturnToPage());
        }
    }

    public function profileAction()
    {
        $user_info  = User::findByID($_SESSION['user_id']);
        
        $group_info     = Contribution::viewGroupTotalContri($user_info['belonging_group']);
        $group_members     = Group::groupMembers($user_info['belonging_group']);

        $members_info = array();
        foreach ($group_members as $key => $value) {
            $id     = $value['id'];
            $name   = $value['name'];

            $contri_info = Contribution::viewMemberTotalContri($value['id']);
                    
            $total_contri = $contri_info[0]['total_contri'];
            $total_month_int = $contri_info[0]['total_month_int'];
            $total_contri_w_int = $contri_info[0]['total_contri'] + $contri_info[0]['total_month_int'];


            $members_info[] = ['id' => $id,
                    'name' => $name,
                    'contri' => $total_contri,
                    'month_int' => $total_month_int,
                    'contri_w_int' => $total_contri_w_int];
        }
                
        View::renderTemplate('Home/index-groupleader.html', [
                    
                    'group_info' => $group_info,
                    'group_members' => $group_members,
                    
                    'members_info' => $members_info
                ]);
    }
}
