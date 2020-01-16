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

            $_POST['int_acquired'] =  array_sum($total_paid) - $_POST['principal'];
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
        /*
        echo "<pre>";
        print_r($toUpdateSummary);
        echo "</pre>";
        */
        if ($toUpdateSummary[0]['payment_rcv']) {
            # code...
            $_POST['payment_rcv']   =   $toUpdateSummary[0]['payment_rcv'] + $paid_amount;
        } else {
            # code...
            $_POST['payment_rcv']   =  $paid_amount;
        }

        if ($_POST['int_acquired']) {
            # code...

            
            
            if ($toUpdateSummary[0]['interest_earned']) {
                # code...
                $_POST['interest_earned'] = $toUpdateSummary[0]['interest_earned'] + $_POST['int_acquired'];
            } else {
                # code...
                $_POST['interest_earned'] = $_POST['int_acquired'];
            }
        } else {
            # code...
            if ($toUpdateSummary[0]['interest_earned']) {
                # code...
                $_POST['interest_earned'] = $toUpdateSummary[0]['interest_earned'];
            } else {
                # code...
                $_POST['interest_earned'] = "";
            }
        }
        
        $_POST['date']          =   $borrowInfo[0]['date'];

        $update_summary = new Summary($_POST);

        $update_summary->update();

        #
        // Update Contribution Month interest

        $toUpdateContribution = Contribution::view($borrowInfo[0]['date']);
        echo $borrowInfo[0]['date'];
        //echo $toUpdateContribution;
        echo "<pre>";
        print_r($toUpdateContribution);
        echo "</pre>";

        # NOTE to be done 11/11/19
        //
        // yung total cont w/out int pag 10-15 no check prev pag 10-30 need check prev 
        // same goes sa total_int at total_contri_w_int

        $contributor_num = count($toUpdateContribution);

        if ($_POST['interest_earned']) {
            # code...
            // for or foreach

            

            for ($a=0; $a < $contributor_num; $a++) {
                # code...
                
                $percent = $toUpdateContribution[$a]['contri'] / $toUpdateSummary[0]['contri_wout_int'];
                $_POST['total_contri_wout_int'] = $toUpdateContribution[$a]['contri'];
                $_POST['contribution_id'] = $toUpdateContribution[$a]['contribution_id'];

                if ($toUpdateContribution[$a]['month_int']) {
                    # code...
                    $add_month_int = $percent * $_POST['interest_earned'];
                    $_POST['month_int'] =  $toUpdateContribution[$a]['month_int'] + $add_month_int;

                    $_POST['total_int'] = $toUpdateContribution[$a]['total_int'] + $_POST['month_int'];

                    $add_total_contri_w_int = $contri[$a]['contri'] + $_POST['month_int'];
                    $_POST['total_contri_w_int'] = $toUpdateContribution[$a]['total_contri_w_int'] + $add_total_contri_w_int;

                }else {
                    # code...
                    $_POST['contribution_id'] = $toUpdateContribution[$a]['contribution_id'];
                    $_POST['month_int'] = $percent * $_POST['interest_earned'];
                    $_POST['total_int'] = $_POST['month_int'];
                    $_POST['total_contri_w_int'] = $toUpdateContribution[$a]['contri'] + $_POST['month_int'];
                }


                $update_contribution = new Contribution($_POST);

                $update_contribution->update_month_int();
            }
        }

        //Flash::addMessage('Update payment successful '.array_sum($total_paid));

        //$this->redirect('/payment/view/'.$_POST['borrow_id']);
    }
}
