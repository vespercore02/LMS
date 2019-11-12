<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Borrow as Mborrow;
use \App\Models\Payment;

class Borrow extends Authenticated
{
    /**
     * Items index
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('borrow/index.html');
    }

    public function borrowMonth()
    {
        if (isset($_GET['month'])) {
            # code...

            $borrow          = Mborrow::getMonthBorrowRecords($_GET['month']);

            echo json_encode($borrow);

        }
    }

    public function addBorrower()
    {
        $BorrowerInfo = explode(" - ", $_POST['borrow_by']);

        $_POST['user_id'] = $BorrowerInfo[0];
        $_POST['group'] = $BorrowerInfo[3];

        $_POST['cut_off'] = self::getContriDate($_POST['borrow_date']);
        $_POST['interest'] = self::getInterest($_POST['borrow_interest'], $_POST['borrow_amount']);
        $_POST['remaining'] = self::getRemaining($_POST['borrow_interest'], $_POST['borrow_amount']);


        $borrow = new Mborrow($_POST);
        $result = $borrow->save();
        
        if ($result > 0) {
            //$this->redirect('/signup/success');

            $borrow->updateSummaryBorrow();

            $_POST['borrow_id'] = $result;

            $payment_list = new Payment($_POST);
            
            echo $payment_list->constructPaymentList();
        } else {
            View::renderTemplate('Admin/form.html', [
                'loan' => $loan
            ]);
        }
        
        
    }

    
    public function getRemaining($borrow_interest, $borrow_amount)
    {
        $int = $borrow_amount  *  ($borrow_interest/100);
        $int_monthly = $int * 12;
        $int_acquired = $borrow_amount + $int_monthly;

        return $int_acquired;
    }

    public function getInterest($borrow_interest, $borrow_amount)
    {
        $int = $borrow_amount  *  ($borrow_interest/100);

        return $int;
    }

    public function getContriDate($borrow_date)
    {
        
        
        $month = explode("-", $borrow_date);

        $set_month = $month[1]."-".$month[2];

        print_r($month);
        
        if ($set_month > '10-15' and $set_month < '10-31' or $set_month == '10-15') {
            # code...
            return $month[0]."-10-15";
        }

        if ($set_month > '10-31' and $set_month < '11-15' or $set_month == '10-31') {
            # code...
            return $month[0]."-10-31";
        }

        if ($set_month > '11-15' and $set_month < '11-30' or $set_month == '11-15') {
            # code...
            return $month[0]."-11-15";
        }

        if ($set_month > '11-30' and $set_month < '12-15' or $set_month == '11-30') {
            # code...
            return $month[0]."-11-30";
        }

        if ($set_month > '12-15' and $set_month < '12-31' or $set_month == '12-15') {
            # code...
            return $month[0]."-12-15";
        }

        if ($set_month > '12-31' and $set_month < '01-15' or $set_month == '12-31') {
            # code...
            return $month[0]."-12-31";
        }

        if ($set_month > '01-15' and $set_month < '01-31' or $set_month == '01-15') {
            # code...
            return $month[0]."-01-15";
        }

        if ($set_month > '01-31' and $set_month < '02-15' or $set_month == '01-31') {
            # code...
            return $month[0]."-01-31";
        }

        if ($set_month > '02-15' and $set_month < '02-28' or $set_month == '02-15') {
            # code...
            return $month[0]."-02-15";
        }

        if ($set_month > '02-28' and $set_month < '03-15' or $set_month == '02-28') {
            # code...
            return $month[0]."-02-28";
        }

        if ($set_month > '03-15' and $set_month < '03-31' or $set_month == '03-15') {
            # code...
            return $month[0]."-03-15";
        }

        if ($set_month > '03-31' and $set_month < '04-15' or $set_month == '03-31') {
            # code...
            return $month[0]."-03-31";
        }

        if ($set_month > '04-15' and $set_month < '04-30' or $set_month == '04-15') {
            # code...
            return $month[0]."-04-15";
        }

        if ($set_month > '04-30' and $set_month < '05-15' or $set_month == '04-30') {
            # code...
            return $month[0]."-04-30";
        }

        if ($set_month > '05-15' and $set_month < '05-31' or $set_month == '05-15') {
            # code...
            return $month[0]."-05-15";
        }

        if ($set_month > '05-31' and $set_month < '06-15' or $set_month == '05-31') {
            # code...
            return $month[0]."-05-31";
        }

        if ($set_month > '06-15' and $set_month < '06-30' or $set_month == '06-15') {
            # code...
            return $month[0]."-06-15";
        }

        if ($set_month > '06-30' and $set_month < '07-15' or $set_month == '06-30') {
            # code...
            return $month[0]."-06-30";
        }

        if ($set_month > '07-15' and $set_month < '07-31' or $set_month == '07-15') {
            # code...
            return $month[0]."-07-15";
        }

        if ($set_month > '07-31' and $set_month < '08-15' or $set_month == '07-31') {
            # code...
            return $month[0]."-07-31";
        }

        if ($set_month > '08-15' and $set_month < '08-31' or $set_month == '08-15') {
            # code...
            return $month[0]."-08-15";
        }

        if ($set_month > '08-31' and $set_month < '09-15' or $set_month == '08-31') {
            # code...
            return $month[0]."-08-31";
        }

        if ($set_month > '09-15' and $set_month < '09-30' or $set_month == '09-15') {
            # code...
            return $month[0]."-09-15";
        }

        if ($set_month > '09-30' and $set_month < '10-15' or $set_month == '09-30') {
            # code...
            return $month[0]."-09-30";
        }
        
    }
}
