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
            $borrowInfo = Borrow::borrowInfo($this->route_params['id']);
            $paymentlist = Mpayment::paymentlist($this->route_params['id']);
            $userInfo   = User::searchById($borrowInfo[0]['user_id']);

            View::renderTemplate('payment/index.html', [
                'userInfo' => $userInfo,
                'borrowInfo' => $borrowInfo,
                'paymentlist' => $paymentlist
            ]);
        /*
        echo "<pre>";
        print_r($paymentlist);
        echo "</pre>";
            */
        } else {
            $this->redirect(Auth::getReturnToPage());
        }
    }

    public function paymentAction()
    {
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
        /*
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        */
        $update = new Mpayment($_POST);

        $update->paymentUpdate();

        $toUpdateList = Mpayment::paymentList($_POST['borrow_id']);
        /*
        echo "<pre>";
        print_r($toUpdateList);
        echo "</pre>";
        */
        $total_paid = array();
        foreach ($toUpdateList as $key => $value) {
            # code...
            /*
            echo $value['id']."<br>";
            echo $value['amount_to_be_paid'] - $_POST['amount']."<br>";
            echo $value['amount_to_be_paid']."<br>";
            echo $value['date_of_payment']."<br>";
            echo $value['borrow_id']."<br>";
            */
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

                //echo $_POST['id'] ." ". $_POST['amount_to_be_paid'] ." ". $_POST['date_to_pay'] ." ". $_POST['amount']. "<br>";
            }
        }

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
            $_POST['int_acquired'] = "";
        }

        $_POST['remaining'] = $_POST['amount_to_be_paid'];
        $_POST['payment'] = array_sum($total_paid);

        $update_borrow  = new Borrow($_POST);

        $update_borrow->update();

        $borrowInfo = Borrow::borrowInfo($_POST['borrow_id']);
        /*
        echo "<pre>";
        print_r($borrowInfo);
        echo "</pre>";
        */
        $toUpdateSummary = Summary::getMonthSummaryRecords($borrowInfo[0]['date']);

        //echo $toUpdateSummary[0]['payment_rcv']."<br>";
        
        echo "<pre>";
        print_r($toUpdateSummary);
        echo "</pre>";
        
        if ($toUpdateSummary['payment_rcv']) {
            # code...
            $_POST['payment_rcv']   =   $toUpdateSummary['payment_rcv'] + $paid_amount;
        } else {
            # code...
            $_POST['payment_rcv']   =  $paid_amount;
        }

        echo $_POST['int_acquired']."<br>";

        if (!empty($_POST['int_acquired'])) {
            # code...
            echo "may kita na <br>";
            if ($toUpdateSummary['interest_earned'] != 0) {
                # code...
                $_POST['interest_earned'] = $toUpdateSummary['interest_earned'] + $_POST['int_acquired'];

                echo "dagdag kita! ". $_POST['interest_earned']." <br>";
            } else {
                # code...
                $_POST['interest_earned'] = $_POST['int_acquired'];

                echo "Unang kita ". $_POST['interest_earned']." <br>";
            }
        } else {
            # code...
            if ($toUpdateSummary['interest_earned']) {
                # code...
                $_POST['interest_earned'] = $toUpdateSummary['interest_earned'];
            } else {
                # code...
                $_POST['interest_earned'] = "";
            }

            //echo "wala walang";
        }

        //echo "wah";
        
        $_POST['date']          =   $borrowInfo[0]['date'];

        $update_summary = new Summary($_POST);

        $update_summary->update();

        #
        

        //echo $_POST['interest_earned'];
        if (!empty($_POST['interest_earned'])) {
            # code...
            echo "Distribution ng Kita <br>";
            // Update Contribution Month interest

            $toUpdateContribution = Contribution::view($borrowInfo[0]['date']);
            //echo $borrowInfo[0]['date'];
            //echo $toUpdateContribution;
            echo "<pre>";
            print_r($toUpdateContribution);
            echo "</pre>";

            # NOTE to be done 11/11/19
            //
            // yung total cont w/out int pag 10-15 no check prev pag 10-30 need check prev
            // same goes sa total_int at total_contri_w_int

            $contributor_num = count($toUpdateContribution);

            // for or foreach
            
            for ($a=0; $a < $contributor_num; $a++) {
                # code...
                
                #re-calculating percentage of contribution for month_int of latest interest_earned
                $percent = $toUpdateContribution[$a]['contri'] / $toUpdateSummary['contri_wout_int'];

                echo $percent." pursyento <br>";

                $_POST['total_contri_wout_int'] = $toUpdateContribution[$a]['contri'];
                
                $_POST['contribution_id'] = $toUpdateContribution[$a]['contribution_id'];

                $update_month_int = $percent * $_POST['int_acquired'];

                /* wrong move kasi nagcalculate na iadd pa maluluge business sobra distribution ng kita

                if (!empty($toUpdateContribution[$a]['month_int'])) {
                    # code...
                    $add_month_int = $percent * $_POST['interest_earned'];

                    echo $_POST['interest_earned']."<br>";

                    $updated_month_int = $toUpdateContribution[$a]['month_int'] + $add_month_int;

                    echo $toUpdateContribution[$a]['month_int']."<br>";

                    $_POST['month_int'] =  number_format($updated_month_int, 2, '.', '');

                    echo $_POST['month_int'] ;

                    # need to check if this $toUpdateContribution[$a]['total_int'] is whole number or with decimal
                    if (preg_match('/\.\d{2,}/', $toUpdateContribution[$a]['total_int'])) {
                        # Successful match
                        $total_int = sprintf("%0.2f", $toUpdateContribution[$a]['total_int']);
                    } else {
                        $total_int = $toUpdateContribution[$a]['total_int'];
                    }

                    $_POST['total_int'] = $total_int + $_POST['month_int'];

                    $add_total_contri_w_int = $toUpdateContribution[$a]['contri'] + $_POST['month_int'];

                    $_POST['total_contri_w_int'] = $toUpdateContribution[$a]['total_contri_w_int'] + $add_total_contri_w_int;

                } else {
                    # code...
                    $_POST['contribution_id'] = $toUpdateContribution[$a]['contribution_id'];
                    $updated_month_int = $percent * $_POST['interest_earned'];
                    $_POST['month_int'] =  number_format($updated_month_int, 2, '.', '');
                    $_POST['total_contri_w_int'] = $toUpdateContribution[$a]['contri'] + $_POST['month_int'];
                }
                */


                echo $update_month_int. "<br>";
                

                if (!empty($toUpdateContribution[$a]['month_int'])) {
                    # code...

                    $latest_update_month_int = floatval($toUpdateContribution[$a]['month_int']) + $update_month_int;

                    $_POST['month_int'] = number_format($latest_update_month_int, 2, '.', '');

                }else {
                    # code...
                    $_POST['month_int'] =  number_format($update_month_int, 2, '.', '');
                }

                echo $_POST['month_int']."<br>";

                $_POST['user_id'] = $toUpdateContribution[$a]['user_id'];

                # iupdate ang month_int

                $update_contribution = new Contribution($_POST);

                $update_contribution->update_month_int();

                # NEXT 1/22/2020
                #1/21/2020
                #update all contributor's total_int and total_contri_w_int

                echo $updated_total_int = self::update_contributed_dates($toUpdateContribution[$a]['user_id'], $borrowInfo[0]['date'], $term, $_POST['month_int'] );



                // get contribution info per month
                /*
                $get_contributions = Contribution::viewMember($toUpdateContribution[$a]['user_id']);

                //echo "<pre>";
                //print_r($get_contributions);
                //echo "</pre>";

                $contribution_info_num = count($get_contributions);

                for ($b=0; $b < $contribution_info_num; $b++) {
                    # code...
                    if ($get_contributions[$b]['contri_date'] >=  $toUpdateContribution[$a]['contri_date']) {
                        # code...
                        echo "Contri here ".$get_contributions[$b]['contri_date']."<br>";

                        $_POST['contribution_id'] = $get_contributions[$b]['contribution_id'];

                        $update_contribution = new Contribution($_POST);

                        $update_contribution->update_month_int();
                    }
                }
                */
            }
        }

        //Flash::addMessage('Update payment successful '.array_sum($total_paid));

        //$this->redirect('/payment/view/'.$_POST['borrow_id']);
    }


    function update_contributed_dates($id, $date, $term, $month_int)
    {
        $_POST['user_id'] = $id;
        $_POST['term'] = $term;

        $update_contributed_date = new Contribution($_POST);

        $contributed_dates = $update_contributed_date->getAllContributedDate();

        $num_dates = count($contributed_dates);

        for ($cd=0; $cd < $num_dates; $cd++) { 
            # code...
            
            if ($contributed_dates[$cd] >= $date) {
                # code...
                if (!empty($contributed_dates[$cd]['total_int'])) {
                    # code...
                    echo "Meron <br>";

                    $_POST['total_int'] = $contributed_dates[$cd]['total_int'] + $month_int;
                } else {
                    # code...
                    echo "Wala <br>";
    
                    $_POST['total_int'] = $month_int;
                }

                $_POST['total_contri_w_int'] = $_POST['total_int'] + $contributed_dates[$cd]['contri'];

                echo $_POST['total_int']."<br>";
                echo $_POST['total_contri_w_int']."<br>";

                $_POST['contribution_id'] = $contributed_dates[$cd]['contribution_id'];

                $update_contributed_date = new Contribution($_POST);

                $update_contributed_date->update_total_int();
            }
            
        }


    }
}