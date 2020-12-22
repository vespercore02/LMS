<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Borrow;
use \App\Models\Summary;
use \App\Models\Contribution;
use \App\Models\Payment as Mpayment;
use \App\Auth;
use \App\Flash;

class Payment extends Authenticated
{
    
    /**
     * View index
     *
     * @return void
     */
    public function viewAction()
    {
        if (isset($this->route_params['id'])) {
            $session_info = User::findByID($_SESSION['user_id']);
            //print_r($session_info);
            $borrowInfo = Borrow::borrowInfo($this->route_params['id']);
            $paymentlist = Mpayment::paymentlist($this->route_params['id']);
            $userInfo   = User::searchById($this->route_params['id']);

            //print_r($userInfo);

            if ($this->route_params['id'] == $_SESSION['user_id']) {
                # code...
                $viewer = "";
            }  elseif ($session_info['belonging_group'] == $session_info['belonging_group'] and $session_info['access_rights'] == 2) {
                # code...
                $viewer = 1;
            }else {
                # code...
                Flash::addMessage('Unauthorized to access', Flash::WARNING);

                $this->redirect(Auth::getReturnToPage());
            }

            View::renderTemplate('payment/index.html', [
                'viewer'    => $viewer,
                'userInfo' => $userInfo,
                'borrowInfo' => $borrowInfo,
                'paymentlist' => $paymentlist
            ]);
       
        } else {
            # code...
            $this->redirect(Auth::getReturnToPage());
        }
    }

    public function paymentAction()
    {
        ###### Update payment of the selected date ######
        $toUpdateList = Mpayment::paymentList($_POST['borrow_id']);
        
        $overall_amount_to_paid = $toUpdateList[count($toUpdateList)-1]['amount_to_be_paid'];

        $date_to_pay = explode(" - ", $_POST['date_to_pay']);

        $term = $_POST['term'];

        $_POST['date_to_pay'] = $date_to_pay[0];

        $_POST['id'] = $date_to_pay[1];

        $paid_amount = $_POST['amount'];

        $_POST['amount_to_be_paid'] = $date_to_pay[2] - $paid_amount ;

        if ($_POST['amount_to_be_paid'] < 0) {
            # code...
            Flash::addMessage('Please pay exact amount', Flash::WARNING);

            $this->redirect('/payment/view/'.$_POST['borrow_id']);

            return;
        } elseif ($_POST['amount_to_be_paid'] == 0) {
            # code...
            $_POST['amount_to_be_paid'] = "Paid";
        }
        $update = new Mpayment($_POST);
        $update->paymentUpdate();

        ###### Code till here #####
        
        $toUpdateList = Mpayment::paymentList($_POST['borrow_id']);
        
        ###### Update payment of the dates after the selected date #####
        $total_paid = array();
        foreach ($toUpdateList as $key => $value) {
            # code...
            
            $total_paid[] = $value['amount_paid'];
            if ($value['id'] > $date_to_pay[1]) {
                # code...
                $_POST['id']                = $value['id'];
                $_POST['date_to_pay']       = $value['date_of_payment'];
                $_POST['amount']            = "";

                if ($_POST['amount_to_be_paid'] != "Paid") {
                    # code...
                    $_POST['amount_to_be_paid'] = $value['amount_to_be_paid'] - $paid_amount;
                }

                $update = new Mpayment($_POST);

                $update->paymentUpdate();

            }
        }
        ##### Code till here ######

        ##### Update borrow information ######
        if ($_POST['amount_to_be_paid'] == "Paid") {
            # code...

            if (array_sum($total_paid) == 0) {
                # code...
                $_POST['int_acquired'] =  $paid_amount - $_POST['principal'];
            } else {
                # code...
                $_POST['int_acquired'] =  array_sum($total_paid) - $_POST['principal'];
            }
        } else {
            # code...
            $_POST['int_acquired'] = 0;
        }

        $_POST['remaining'] = $_POST['amount_to_be_paid'];
        $_POST['payment'] = array_sum($total_paid);

        $update_borrow  = new Borrow($_POST);

        $update_borrow->update();

        ###### Code Till Here ######

        ###### Update Summary Record of the Month ######

        $borrowInfo = Borrow::borrowInfo($_POST['borrow_id']);
        
        print_r($borrowInfo);
        //echo $_POST['date_to_pay'];
        $toUpdateSummary = Summary::getMonthSummaryRecords($_POST['date']);
        /*
        echo "<pre>";
        print_r($toUpdateSummary);
        echo "</pre>";
        */
        if ($toUpdateSummary['payment_rcv']) {
            # code...
            $_POST['payment_rcv']   =   $toUpdateSummary['payment_rcv'] + $paid_amount;
        } else {
            # code...
            $_POST['payment_rcv']   =  $paid_amount;
        }

        if ($toUpdateSummary['deficit']) {
            # code...

            if ($_POST['amount_to_be_paid'] == "Paid") {

                //echo $toUpdateSummary['deficit']." ".$overall_amount_to_paid;

                $_POST['deficit'] = $toUpdateSummary['deficit'] - $overall_amount_to_paid;
                
                $_POST['est_earned'] = $toUpdateSummary['est_earned'] - $overall_amount_to_paid ;

                if (0 > $_POST['est_earned']) {
                    # code...
                    $_POST['est_earned'] = 0;
                }
                
            }else {
                # code...
                $_POST['deficit'] = $toUpdateSummary['deficit'] - $paid_amount;

                $_POST['est_earned'] = $toUpdateSummary['est_earned'];
            }


        }else {
            # code...
            $_POST['deficit'] = 0;

            $_POST['est_earned'] = $toUpdateSummary['est_earned'];

        }

        //echo $_POST['int_acquired']."<br>";

        if (!empty($_POST['int_acquired'])) {
            # code...
            //echo "may kita na <br>";
            if ($toUpdateSummary['interest_earned'] != 0) {
                # code...
                $_POST['interest_earned'] = $toUpdateSummary['interest_earned'] + $_POST['int_acquired'];

                $_POST['interest_earned'] = number_format($_POST['interest_earned'], 2, '.', '');

                $_POST['total'] = $_POST['interest_earned'] + $toUpdateSummary['contri_wout_int'];
              //  echo "dagdag kita! ". $_POST['interest_earned']." <br>";

                $_POST['total'] = number_format($_POST['total'], 2, '.', '');
            } else {
                # code...
                $_POST['interest_earned'] = $_POST['int_acquired'];

                $_POST['interest_earned'] = number_format($_POST['interest_earned'], 2, '.', '');

                $_POST['total'] = $_POST['interest_earned'] + $toUpdateSummary['contri_wout_int'];

                //echo "Unang kita ". $_POST['interest_earned']." <br>";

                $_POST['total'] = number_format($_POST['total'], 2, '.', '');
            }
        } else {
            # code...
            if ($toUpdateSummary['interest_earned']) {
                # code...
                $_POST['interest_earned'] = $toUpdateSummary['interest_earned'];

                $_POST['total'] = 0;
                
                $_POST['total'] = number_format($_POST['total'], 2, '.', '');

            } else {
                # code...
                $_POST['interest_earned'] = "";
                
                $_POST['total'] = 0;
                
                $_POST['total'] = number_format($_POST['total'], 2, '.', '');
            }

            
        }
        
        $_POST['date']          =   $_POST['date'];

        
        $update_summary = new Summary($_POST);

        $update_summary->update();

        ###### Code Till here ######

        ###### Update Contributors earning ######
        if ($_POST['interest_earned'] > $toUpdateSummary['interest_earned']) {
            # code...
           // echo "Distribution ng Kita <br>";

            $toUpdateContribution = Contribution::view($_POST['date']);
            
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

        ###### Code Till Here ######
                
        self::getEstEarned($_POST['date']);

        Flash::addMessage('Update payment successful '.array_sum($total_paid));

        $this->redirect('/payment/view/'.$_POST['borrow_id']);
        
    }


    public static function update_contributed_dates($id, $date, $term, $month_int)
    {
        $_POST['user_id'] = $id;
        $_POST['term'] = $term;

        $update_contributed_date = new Contribution($_POST);

        $contributed_dates = $update_contributed_date->getAllContributedDate();

        $num_dates = count($contributed_dates);

        $_POST['total_int'] = "";
        $_POST['total_contri_w_int'] = "";
        for ($cd=0; $cd < $num_dates; $cd++) { 
            # code...
            //echo $contributed_dates[$cd]['contri_date']."<br>";
            //echo $contributed_dates[$cd]['total_int']."<br>";
            //echo $month_int."<br>";
            if ($contributed_dates[$cd]['contri_date'] >= $date) {
                # code...
                if (!empty($contributed_dates[$cd]['total_int'])) {
                    # code...
                    //echo "Meron <br>";

                    //echo $contributed_dates[$cd]['total_int']."<br>";

                    $_POST['total_int'] = $contributed_dates[$cd]['total_int'] + $month_int;
                } else {
                    # code...
                    //echo "Wala <br>";
    
                    $_POST['total_int'] = $month_int;
                }

                $updated_total_contri_w_int = $_POST['total_int'] + $contributed_dates[$cd]['total_contri_wout_int'];

                $_POST['total_contri_w_int'] =  number_format($updated_total_contri_w_int, 2, '.', '');

                //echo $_POST['total_int']." ".$contributed_dates[$cd]['contri_date']."<br>";
                //echo $_POST['total_contri_w_int']." ".$contributed_dates[$cd]['contri_date']."<br>";

                $_POST['contribution_id'] = $contributed_dates[$cd]['contribution_id'];

                $update_contributed_date = new Contribution($_POST);

                $update_contributed_date->update_total_int();
            }
            
        }


    }

    public function getEstEarned($date)
    {
        $summary = new Summary();
        $fetch_data = $summary->getEstEarned($date);

        if (empty($fetch_data['payment_rcv'])) {
            # code...
            $fetch_data['payment_rcv'] = 0;
        }

        if (empty($fetch_data['interest_earned'])) {
            # code...
            $fetch_data['interest_earned'] = 0;
        }

        $est_earned = $fetch_data['payment_rcv'] + $fetch_data['deficit'] - $fetch_data['amount_borrow'] - $fetch_data['interest_earned'];
        echo "<pre>";
        print_r($summary->getEstEarned($date));
        echo "</pre>";

        echo $est_earned;

        $fetch_data = $summary->updateEstEarned($date, $est_earned);

    }
}
