<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Contribution;
use \App\Models\Summary;
use \App\Models\Term;
use \App\Models\User;

class Contributions extends Authenticated
{
    public function indexAction()
    {
        $terms = new Term();

        $terms_list = $terms->view();
        
        View::renderTemplate('/contribution/index.html', [
            'terms' => $terms_list
        ]);
    }

    public function termAction()
    {
        $term = $this->route_params['id'];
        $contribution   = Contribution::viewTerm($this->route_params['id']);
        $term_months    = Term::months($this->route_params['id']);
        
        $contri_months = array();

        foreach ($term_months as $month) {
            # code...

            $contri_months[]  = Contribution::getMonthContriRecords($month['month_start']);
        }
        /*
        echo "<pre>";
        print_r($contri_months);
        echo "</pre>";
        */
        View::renderTemplate('/contribution/term.html', [
            'term' => $term,
            'term_months' => $term_months,
            'contri_months' => $contri_months
        ]);
    }

    public function monthAction()
    {
        $month = $this->route_params['id'];
        $month_contri   = Contribution::getMonthContriRecords($this->route_params['id']);
        
        View::renderTemplate('/contribution/month.html', [
            'month' => $month,
            'month_contri' => $month_contri
        ]);
    }

    
    public function termMonths()
    {
        if (isset($_GET['term'])) {
            # code...

            $term_months          = Term::months($_GET['term']);

            echo json_encode($term_months);
        }
    }
    public function termContribution()
    {
        if (isset($_GET['term'])) {
            # code...

            $term_contri          = Contribution::viewTerm($_GET['term']);

            echo json_encode($term_contri);
        }
    }
    
    public function addAction()
    {
        $contribution = new Contribution($_POST);

        $selected_month = $_POST['month'];
        $_POST['contri'] = number_format($_POST['contri'], 2, '.', '');
        $given_contri   = $_POST['contri'];

        $month = $_POST['month'];

        $months = Term::months($_POST['term']);

        $months_count = count($months);

        $contri_info = $contribution->checkContriDate();

        if ($contri_info) {
            # code...
            echo "meron";
        }else {
            # code...
            echo "wala";

            $last_contri_info = $contribution->getLastContri();

            $_POST['month_int'] = "0.00";
            $_POST['total_int'] = "0.00";


            if ($last_contri_info) {
                # code...
                
                echo "<pre>";
                print_r( $last_contri_info);
                echo "</pre>";

                if (!empty($last_contri_info['total_contri_wout_int'])) {
                    # code...
                    
                    echo $_POST['total_contri_wout_int'] = $_POST['contri'] + $last_contri_info['total_contri_wout_int'];

                    $_POST['total_contri_wout_int'] = number_format($_POST['total_contri_wout_int'], 2, '.', '');

                    $_POST['total_contri_w_int'] = $_POST['contri'] + $last_contri_info['total_contri_wout_int'] + $last_contri_info['total_int'];
                    
                    $_POST['total_contri_w_int'] = number_format($_POST['total_contri_w_int'], 2, '.', '');
                    
                    $_POST['total_int'] = $last_contri_info['total_int'];

                }else {
                    # code...
                    
                    echo $_POST['total_contri_wout_int'] = $_POST['contri'] + 0;

                    $_POST['total_contri_wout_int'] = number_format($_POST['total_contri_wout_int'], 2, '.', '');

                    $_POST['total_contri_w_int'] = $_POST['contri'];
                    
                    $_POST['total_contri_w_int'] = number_format($_POST['total_contri_w_int'], 2, '.', '');
                }


            }else {
                # code...

                echo $_POST['total_contri_wout_int'] = $_POST['contri'];

                $_POST['total_contri_wout_int'] = number_format($_POST['total_contri_wout_int'], 2, '.', '');

                $_POST['total_contri_w_int'] = $_POST['contri'];
                
                $_POST['total_contri_w_int'] = number_format($_POST['total_contri_w_int'], 2, '.', '');
            }

            $contribution = new Contribution($_POST);

            $contribution->add();
            
            $contribution->updateTotalContriWoutInt();
        }

        ### GET ALL CONTRIBUTION from DATE SELECTED

        echo "<pre>";
                print_r( Contribution::view($selected_month));
                echo "</pre>";

        $dateOverallContri = Contribution::view($selected_month);

        $dateTotalContri = array();

        foreach ($dateOverallContri as $memberContri) {
            # code...
            $dateTotalContri[] = $memberContri['contri'];

        }

        ### Update Summary

        $summary_info = Summary::getMonthSummaryRecords($selected_month);

        if ($summary_info) {
            # code...
            $_POST['month']     = $selected_month;
            $_POST['contri']    = array_sum($dateTotalContri);
            $_POST['contri']    = number_format($_POST['contri'], 2, '.', '');

            $summary = new Summary($_POST);

            $summary->updateContri();

            $update_month_summary = self::Update_month_summary($selected_month);

            //echo "update";
        } else {
            # code...
            $_POST['month']     = $selected_month;
            $_POST['contri']    = array_sum($dateTotalContri);
            $_POST['contri']    = number_format($_POST['contri'], 2, '.', '');

            $_POST['contri_wout_int'] = $_POST['contri'];
            $_POST['amount_borrow']   = "0.00";
            $_POST['payment_rcv']     = "0.00";
            $_POST['deficit']         = "0.00";
            $_POST['interest_earned'] = "0.00";
            $_POST['est_earned']      = "0.00";
            $_POST['total']           = $_POST['contri'];

            $summary = new Summary($_POST);

            $summary->add();

        }

        ### Check Interest Earned 

        if ($summary_info['interest_earned']) {
            # code...
            echo "meron ng kinita";

            $update_month_int = self::Update_month_int($selected_month, $contri_info['term_id']);

            ### Update member/s Spread interest
            ### try to get sampleupdate

        }else {
            # code...
            echo "wala pang kinita";
        }

        Flash::addMessage('Contribution Successfully Added');

        $this->redirect('/members/view/'.$_POST['user_id']);
    }

    /**
     * Functions
     */

    public function editAction()
    {
        //echo $this->route_params['id'];
        
        //echo "<pre>";
        //print_r(Contribution::getContriInfo($this->route_params['id']));
        //echo "</pre>";
        
        $contri_info = Contribution::getContriInfo($this->route_params['id']);
        
        $user_info = User::searchById($contri_info['user_id']);
        /*
        echo "<pre>";
        print_r($user_info);
        echo "</pre>";
        */
        View::renderTemplate('/contribution/edit.html', [
            'contri_info' => $contri_info,
            'user_info' => $user_info[0]
        ]);
    }

    public function updateAction()
    {
        
        $selected_month = $_POST['month'];
        $given_contri   = $_POST['contri'];

        $contribution = new Contribution($_POST);

        $last_contri_info = $contribution->getLastContri();

        $contributed_dates = $contribution->getContributedDate();

        $date_position = self::Search($_POST['month'], $contributed_dates);

        $date_count = count($contributed_dates);
        
        $contri_info = $contribution->getContriDate();

        $removed_previous_contri_from_total = "";

        $last_month_total_int = "";

        if ($_POST['contri'] != $contri_info['contri']) {
            
            $prev_contri = '';
            for ($i=$date_position; $i < $date_count; $i++) {
                # code...
                echo $i." ".$contributed_dates[$i]."<br>";
                
                $_POST['month'] = $contributed_dates[$i];

                $contribution = new Contribution($_POST);

                $contri_info = $contribution->getContriDate();
        
                $last_month_total_int = $contri_info['total_int'];

                if ($contri_info['contri_date'] == $selected_month) {
                    # code...
                    echo "<pre>";
                    print_r($contri_info);
                    echo "</pre>";
                    $_POST['contri'] = $given_contri;

                    if ($contri_info['total_contri_wout_int'] != $contri_info['contri']) {
                        # code...
                        $removed_previous_contri_from_total = $contri_info['total_contri_wout_int'] - $contri_info['contri'];

                        $prev_contri .= $contri_info['contri'];

                        $_POST['total_contri_wout_int'] =  $given_contri + $removed_previous_contri_from_total;
                        
                        $_POST['total_contri_wout_int'] = number_format($_POST['total_contri_wout_int'], 2, '.', '');
                    } else {
                        # code...

                        $_POST['total_contri_wout_int'] =  $given_contri;
                        
                        $_POST['total_contri_wout_int'] = number_format($_POST['total_contri_wout_int'], 2, '.', '');

                        $prev_contri .= $contri_info['contri'];
                    }

                    if ($contri_info['total_int'] == "") {
                        # code...
                        $contri_info['total_int'] = 0 + $last_month_total_int;
                    }else{
                        $contri_info['total_int'] = $contri_info['total_int'] + $last_month_total_int;
                    }

                    $_POST['total_contri_w_int'] = ($_POST['total_contri_wout_int'] + $contri_info['total_int']);

                    //echo $prev_contri."<br>";

                    //echo $removed_previous_contri_from_total."<br>";

                    //echo $_POST['total_contri_wout_int']."<br>";

                    //echo  $contri_info['total_int']."<br>";
    
                    //echo $_POST['total_contri_w_int']."<br>";

                    $update = new Contribution($_POST);

                    $update->update();

                    
                    $summary_info = Summary::getMonthSummaryRecords($contri_info['contri_date']);

                    //echo $summary_info['contri_wout_int']."<br>"; 

                    $removed_previous_contri_from_total_summary = $summary_info['contri_wout_int'] - $contri_info['contri'];

                    $_POST['contri'] = $removed_previous_contri_from_total_summary + $given_contri;
                    $_POST['contri'] = number_format($_POST['contri'], 2, '.', '');

                    //echo $_POST['contri'];

                    $summary = new Summary($_POST);

                    $summary->updateContri();

                    if ($summary_info['interest_earned']) {
                        # code...
                        $update_month_int = self::Update_month_int($selected_month, $contri_info['term_id']);

                        //echo $update_month_int;
                    }
                    

                    $update_month_summary = self::Update_month_summary($selected_month);

                } elseif ($contri_info['contri_date'] > $selected_month) {
                    # code...

                    //echo $contri_info['total_contri_wout_int']."<br>";

                    //echo $prev_contri."1<br>";

                    //echo $given_contri."2<br>";


                    $_POST['total_contri_wout_int'] = ($contri_info['total_contri_wout_int'] - $prev_contri) + $given_contri;

                    $_POST['total_contri_wout_int'] = number_format($_POST['total_contri_wout_int'], 2, '.', '');

                    if ($contri_info['total_int'] == "") {
                        # code...
                        $contri_info['total_int'] = 0;
                    }

                    $_POST['total_contri_w_int'] = ($_POST['total_contri_wout_int'] + $contri_info['total_int']);

                    $_POST['total_contri_w_int'] = number_format($_POST['total_contri_w_int'], 2, '.', '');

                    //echo $prev_contri." ".$given_contri."<br>";
                    //$update_total_int = $_POST['total_int'];

                    //$_POST['total_int'] = number_format($update_total_int, 2, '.', '');

                    //echo $_POST['total_int']."<br>";

                    //echo  $contri_info['total_int']."<br>";

                    //echo $_POST['total_contri_wout_int']."<br>";
    
                    //echo $_POST['total_contri_w_int']."<br>";

                    $update = new Contribution($_POST);

                    $update->update_total();
                }

                
            }

            
        Flash::addMessage('Contribution Successfully Updated');
            
        }else {
            echo "No Change";

            Flash::addMessage('Contribution No Change/s',Flash::WARNING);
        }

        

        $this->redirect('/members/view/'.$_POST['user_id']);

        
    }

    public static function Search($value, $array)
    {
        return(array_search($value, $array));
    }

    public static function Update_month_int($date, $term)
    {

        $summary_info = Summary::getMonthSummaryRecords($date);

        $summary_info['contri_wout_int'];

        $contributor_info = Contribution::getMonthContriRecords($date);
        
        $contributor_count = count($contributor_info);
        
        foreach ($contributor_info as $contributor) {
            # code...
            /*
            echo $contributor['name']."<br>";
            echo $contributor['contri']."<br>";
            echo $contributor['month_int']."<br>";
            echo $contributor['total_int']."<br>";
            */
            $int_percentage = ($contributor['contri'] / $summary_info['contri_wout_int']);

            //echo "int % ".$int_percentage."<br>";

            $percentage = round($int_percentage, 2);

            //echo "% ".$percentage."<br>";

            if (!empty($summary_info['interest_earned'])) {
                # code...
                $month_int = $summary_info['interest_earned'] * $percentage;
                
                $_POST['month_int'] = number_format($month_int, 2, '.', '');
            }else {
                # code...
                $_POST['month_int'] = 0;
            }


            
            $_POST['contribution_id'] = $contributor['contribution_id'];
            //echo $percentage."<br>";
            //echo $contributor['id'];

            if (empty($contributor['month_int'])) {
                # code...
                $contributor['month_int'] = 0;
            }

            if (empty($contributor['total_int'])) {
                # code...
                $contributor['total_int'] = 0;
            }

            $total_int = $contributor['total_int'] - $contributor['month_int'];

            
            //echo $_POST['month_int'] ." ". $contributor['total_int'] ." ". $contributor['month_int']."<br>";
            //echo $_POST['month_int']."<br>";

            //echo $total_int."<br>";

            $month_int = $_POST['month_int'] + $total_int;
            
            $_POST['total_int'] = number_format($month_int, 2, '.', '');

            //echo "Total int ".$total_int."<br>";

            //echo "Month int ".$contributor['month_int']."<br>";

            if ($contributor['total_contri_w_int'] == 0) {
                # code...
                $total_contri_w_int = $_POST['total_int'] + $contributor['total_contri_wout_int'];
            }else {
                # code...
                $total_contri_w_int = $_POST['total_int'] + $contributor['total_contri_wout_int'];
            }

            //echo "total_contri_wout_int ".$contributor['total_contri_wout_int']."<br>";


            $_POST['total_contri_w_int'] = number_format($total_contri_w_int, 2, '.', '');
            /*
            echo "total_contri_w_int ".$_POST['total_contri_w_int']."<br>";

            echo "<br>";
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            */
            $contribution = new Contribution($_POST);

            $contribution->update_month_int();

            $contribution->update_total_int();

            $contributionList = Contribution::viewMemberTerm($contributor['id'], $term);

            foreach ($contributionList as $contribution) {
                # code...
                //echo $contribution['contri_date']."<br>";

                $_POST['contribution_id'] = $contribution['contribution_id'];

                if ($contribution['contri_date'] > $date) {
                    # code...

                    if (empty($contribution['month_int'])) {
                        # code...
                        $contribution['month_int'] = 0;
                    }

                    $total_int = $contribution['month_int'] + $_POST['month_int'];

                    $_POST['total_int'] = number_format($total_int, 2, '.', '');

                    //echo $_POST['total_int']."<br>";
                    

                    if (empty($contributor['total_contri_w_int'])) {
                        # code...
                        $contributor['total_contri_w_int'] = 0;
                        //echo $contributor['total_contri_w_int']."<br>";
                    }
        
                    if (empty($contributor['total_int'])) {
                        # code...
                        $contributor['total_int'] = 0;
                        //echo $contributor['total_int']."<br>";
                    }
                    /*
                    if ($_POST['total_int'] == "0.00") {
                        # code...
                        $_POST['total_int']  = 0;
                        //echo $_POST['total_int']."<br>";
                    }
                    */

                    $total_contri_w_int = (float)$contribution['total_contri_wout_int'] + (float)$_POST['total_int'] ;

                    $_POST['total_contri_w_int'] = number_format($total_contri_w_int, 2, '.', '');
                    /*
                    echo "new total contri w int ".$_POST['total_contri_w_int']."<br>";

                    
                    echo "<pre>";
                    print_r($_POST);
                    echo "</pre>";  
                    */
                    $contribution = new Contribution($_POST);

                    //$contribution->update_month_int();

                    $contribution->update_total_int();
                }

                echo "<br>";
            }

        }

        return $_POST['month_int'];

    }

    public static function Update_month_total_int($date)
    {

    }

    public static function Update_month_summary($date)
    {
        $summary_info = Summary::getMonthSummaryRecords($date);

        $_POST['payment_rcv'] = $summary_info['payment_rcv'];
        $_POST['deficit'] = $summary_info['deficit'];
        $_POST['interest_earned'] = $summary_info['interest_earned'];

        if ($summary_info['interest_earned'] == "") {
            # code...
            $summary_info['interest_earned'] = 0;
        }
        $_POST['total'] = $summary_info['interest_earned'] + $summary_info['contri_wout_int'];
        $_POST['total'] = number_format($_POST['total'], 2, '.', '');
        $_POST['date'] = $date;

        $update_summary = new Summary($_POST);

        $update_summary->update();
    }

    public static function UpdateAll($date)
    {
        $toUpdateSummary = Summary::getMonthSummaryRecords($date);

        $toUpdateContribution = Contribution::view($date);
            
        $contributor_num = count($toUpdateContribution);

        for ($a=0; $a < $contributor_num; $a++) {
            # code...
            //echo $toUpdateContribution[$a]['contri']."<br>";
            //echo $toUpdateSummary['contri_wout_int']."<br>";
            $percentage = $toUpdateContribution[$a]['contri'] / $toUpdateSummary['contri_wout_int'];

            $percent = number_format($percentage, 2, '.', '');

            //echo $percent."<br>";

            $_POST['total_contri_wout_int'] = $toUpdateContribution[$a]['contri'];
            
            $_POST['contribution_id'] = $toUpdateContribution[$a]['contribution_id'];

            $update_month_int = $percent * $_POST['int_acquired'];
            
            if (!empty($toUpdateContribution[$a]['month_int'])) {
                # code...

                $latest_update_month_int = floatval($toUpdateContribution[$a]['month_int']) + $update_month_int;

                $_POST['month_int'] = number_format($latest_update_month_int, 2, '.', '');

            }else {
                # code...
                $_POST['month_int'] =  number_format($update_month_int, 2, '.', '');
            }

            //echo $_POST['month_int']."<br>";

            $_POST['user_id'] = $toUpdateContribution[$a]['user_id'];

            # iupdate ang month_int

            $update_contribution = new Contribution($_POST);

            $update_contribution->update_month_int();
            
            self::update_contributed_dates($toUpdateContribution[$a]['user_id'], $_POST['date'], $term, $update_month_int );

        }
    }

    ########## APPLY MO TO SA UPDATE MONTH INT 11-18-2020
 
    public function sampleupdateAction()
    {
        $summary_info = Summary::getMonthSummaryRecords($this->route_params['id']);

        $contributor_info = Contribution::getMonthContriRecords($this->route_params['id']);

        foreach ($contributor_info as $contributor) {
            # code...
            echo $contributor['name']."<br>";
            echo $contributor['contri']."<br>";
            echo $contributor['month_int']."<br>";
            echo $contributor['total_int']."<br>";

            $int_percentage = ($contributor['contri'] / $summary_info['contri_wout_int']);

            echo "int % ".$int_percentage."<br>";

            $percentage = number_format($int_percentage, 2, '.', '');

            echo "% ".$percentage."<br>";

            if (!empty($summary_info['interest_earned'])) {
                # code...
                $month_int = $summary_info['interest_earned'] * $percentage;
                
                $_POST['month_int'] = number_format($month_int, 2, '.', '');
            }else {
                # code...
                $_POST['month_int'] = 0;
            }


            
            $_POST['contribution_id'] = $contributor['contribution_id'];
            //echo $percentage."<br>";
            //echo $contributor['id'];

            if (empty($contributor['month_int'])) {
                # code...
                $contributor['month_int'] = 0;
            }

            if (empty($contributor['total_int'])) {
                # code...
                $contributor['total_int'] = 0;
            }

            $total_int = $contributor['total_int'] - $contributor['month_int'];

            
            //echo $_POST['month_int'] ." ". $contributor['total_int'] ." ". $contributor['month_int']."<br>";
            echo $_POST['month_int']."<br>";

            echo $total_int."<br>";

            $month_int = $_POST['month_int'] + $total_int;
            
            $_POST['total_int'] = number_format($month_int, 2, '.', '');

            echo "Total int ".$total_int."<br>";

            echo "Month int ".$contributor['month_int']."<br>";

            if ($contributor['total_contri_w_int'] == 0) {
                # code...
                $total_contri_w_int = $_POST['total_int'] + $contributor['total_contri_wout_int'];
            }else {
                # code...
                $total_contri_w_int = $_POST['total_int'] + $contributor['total_contri_wout_int'];
            }

            echo "total_contri_wout_int ".$contributor['total_contri_wout_int']."<br>";


            $_POST['total_contri_w_int'] = number_format($total_contri_w_int, 2, '.', '');

            echo "total_contri_w_int ".$_POST['total_contri_w_int']."<br>";

            echo "<br>";
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";

            $contribution = new Contribution($_POST);

            $contribution->update_month_int();

            $contribution->update_total_int();

            $contributionList = Contribution::viewMemberTerm($contributor['id'], "2020");

            foreach ($contributionList as $contribution) {
                # code...
                echo $contribution['contri_date']."<br>";

                $_POST['contribution_id'] = $contribution['contribution_id'];

                if ($contribution['contri_date'] > $this->route_params['id']) {
                    # code...

                    if (empty($contribution['month_int'])) {
                        # code...
                        $contribution['month_int'] = 0;
                    }

                    $total_int = $contribution['month_int'] + $_POST['month_int'];

                    $_POST['total_int'] = number_format($total_int, 2, '.', '');

                    //echo $_POST['total_int']."<br>";
                    

                    if (empty($contributor['total_contri_w_int'])) {
                        # code...
                        $contributor['total_contri_w_int'] = 0;
                        //echo $contributor['total_contri_w_int']."<br>";
                    }
        
                    if (empty($contributor['total_int'])) {
                        # code...
                        $contributor['total_int'] = 0;
                        //echo $contributor['total_int']."<br>";
                    }
                    /*
                    if ($_POST['total_int'] == "0.00") {
                        # code...
                        $_POST['total_int']  = 0;
                        //echo $_POST['total_int']."<br>";
                    }
                    */

                    $total_contri_w_int = (float)$contribution['total_contri_wout_int'] + (float)$_POST['total_int'] - (float)$contribution['total_int'];

                    $_POST['total_contri_w_int'] = number_format($total_contri_w_int, 2, '.', '');
                    
                    echo "new total contri w int ".$_POST['total_contri_w_int']."<br>";

                    
                    echo "<pre>";
                    print_r($_POST);
                    echo "</pre>";

                    $contribution = new Contribution($_POST);

                    //$contribution->update_month_int();

                    $contribution->update_total_int();
                }

                echo "<br>";
            }

        }
    }
}
