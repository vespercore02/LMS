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
        
        //echo "<pre>";
        //print_r($toUpdateList);
        //echo "</pre>";
        

        $overall_amount_to_paid = $toUpdateList[count($toUpdateList) - 1]['amount_to_be_paid'];
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

        if ($toUpdateSummary['deficit']) {
            # code...

            if ($_POST['amount_to_be_paid'] == "Paid") {

                $_POST['deficit'] = $toUpdateSummary['deficit'] - $overall_amount_to_paid;

            }else {
                # code...
                $_POST['deficit'] = $toUpdateSummary['deficit'] - $paid_amount;
            }


        }else {
            # code...

            //$_POST['deficit'] = 
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

        echo $_POST['interest_earned'];
        if ($_POST['interest_earned'] > $toUpdateSummary['interest_earned']) {
            # code...
            echo "Distribution ng Kita <br>";
            // Update Contribution Month interest

            $toUpdateContribution = Contribution::view($borrowInfo[0]['date']);
            //echo $borrowInfo[0]['date'];
            //echo $toUpdateContribution;
            //echo "<pre>";
            //print_r($toUpdateContribution);
            //echo "</pre>";

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

                //echo $percent." pursyento <br>";

                $_POST['total_contri_wout_int'] = $toUpdateContribution[$a]['contri'];
                
                $_POST['contribution_id'] = $toUpdateContribution[$a]['contribution_id'];

                $update_month_int = $percent * $_POST['int_acquired'];

                //echo $update_month_int. "<br>";
                
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
                
                self::update_contributed_dates($toUpdateContribution[$a]['user_id'], $borrowInfo[0]['date'], $term, $update_month_int );

            }
        }

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
            echo $contributed_dates[$cd]['contri_date']."<br>";
            echo $contributed_dates[$cd]['total_int']."<br>";
            echo $month_int."<br>";
            if ($contributed_dates[$cd]['contri_date'] >= $date) {
                # code...
                if (!empty($contributed_dates[$cd]['total_int'])) {
                    # code...
                    echo "Meron <br>";

                    echo $contributed_dates[$cd]['total_int']."<br>";

                    $_POST['total_int'] = $contributed_dates[$cd]['total_int'] + $month_int;
                } else {
                    # code...
                    echo "Wala <br>";
    
                    $_POST['total_int'] = $month_int;
                }

                $updated_total_contri_w_int = $_POST['total_int'] + $contributed_dates[$cd]['total_contri_wout_int'];

                $_POST['total_contri_w_int'] =  number_format($updated_total_contri_w_int, 2, '.', '');

                echo $_POST['total_int']." ".$contributed_dates[$cd]['contri_date']."<br>";
                echo $_POST['total_contri_w_int']." ".$contributed_dates[$cd]['contri_date']."<br>";

                $_POST['contribution_id'] = $contributed_dates[$cd]['contribution_id'];

                $update_contributed_date = new Contribution($_POST);

                $update_contributed_date->update_total_int();
            }
            
        }


    }
}
