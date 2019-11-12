<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

class Payment extends \Core\Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function paymentUpdate()
    {
        $sql = 'UPDATE payment_records 
        set amount_paid = :amount_paid,
        amount_to_be_paid = :amount_to_be_paid 
        where borrow_id = :borrow_id 
        and date_of_payment = :date_of_payment';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':amount_paid', $this->amount);
        $stmt->bindValue(':amount_to_be_paid', $this->amount_to_be_paid);
        $stmt->bindValue(':borrow_id', $this->borrow_id);
        $stmt->bindValue(':date_of_payment', $this->date_to_pay);

        $stmt->execute();
    }

    public function paymentToUpdateList()
    {
        $sql = 'SELECT * FROM payment_records WHERE borrow_id = :borrow_id and id > :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':borrow_id', $this->borrow_id);
        $stmt->bindValue(':id', $this->id);

        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function paymentList($id)
    {
        $sql = 'SELECT * FROM payment_records WHERE borrow_id = :borrow_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':borrow_id', $id);

        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function constructPaymentList()
    {
        $date = explode('-', $this->cut_off); // explode to get array of YY-MM-DD

        // when trigger a button to change the day value.

        $date[2] = '01'; // this would change the previous value of DD/Day to this one. Or input any value you want to execute when the button is triggered

        // then implode the array again for datetime format.

        $date = implode('-', $date); // that will output '2013-10-10'.

        $principal = $this->borrow_amount;
        $int = $this->interest;
        $y=1;
        $num_term = 12;
        $next_inc = "";
        //start date
        $month_sched = date($date);
        while ($y <= $num_term) {
            //15th
            //echo date("d",strtotime($this->cut_off))."<br>";
            //echo date("m",strtotime($month_sched)) ;
            if (isset($month_line_15)) {
                $month_int = date("Y-m-t", strtotime($month_sched));
                $new_int = $int*$y;
                $monthly_payment = $new_int + $principal;
                echo self::addPaymentlist($month_int, $monthly_payment);
                
                echo $month_int." ".$monthly_payment."<br>";

                $month_sched = date("Y-m-d", strtotime($month_sched." +1month"));
                $month_line_15 = strtotime($month_sched." +14 day");
                $day = date("Y-m-d", $month_line_15);

                echo self::addPaymentlist($day, $monthly_payment);

                echo $day." ".$monthly_payment."<br>";

                $y++;
            } elseif (date("d", strtotime($this->cut_off)) == "15") {
                # code...
                //echo "15 <br>";
                $month_last_day = date("Y-m-t", strtotime($month_sched));
                $monthly_payment = $int + $principal;
                echo self::addPaymentlist($month_last_day, $monthly_payment);

                echo $month_last_day." ".$monthly_payment."<br>";

                $month_sched = date("Y-m-d", strtotime($month_sched." +1month"));
                $month_line_15 = strtotime($month_sched." +14 day");
                $day = date("Y-m-d", $month_line_15);

                echo self::addPaymentlist($day, $monthly_payment);

                echo $day." ".$monthly_payment."<br>";

                $y++;
            }
            
            

            if (isset($month_15)) {
                $month_sched = date("Y-m-d", strtotime($month_sched." +1month"));
                $month_15 = strtotime($month_sched." +14 day");
                $day = date("Y-m-d", $month_15);
                $new_int = $int*$y;
                $monthly_payment = $new_int + $principal;

                echo self::addPaymentlist($day, $monthly_payment);

                echo $day." ".$monthly_payment."<br>";

                $month_int = date("Y-m-t", strtotime($month_sched));

                echo self::addPaymentlist($month_int, $monthly_payment);
                
                echo $month_int." ".$monthly_payment."<br>";
                $y++;
            } elseif ($this->cut_off == date("Y-m-t", strtotime($month_sched))) {
                # code...
                $monthly_payment = $int + $principal;

                $month_sched = date("Y-m-d", strtotime($month_sched." +1month"));
                $month_15 = strtotime($month_sched." +14 day");
                $day = date("Y-m-d", $month_15);

                echo self::addPaymentlist($day, $monthly_payment);

                echo $day." ".$monthly_payment."<br>";

                $month_last_day = date("Y-m-t", strtotime($month_sched));

                echo self::addPaymentlist($month_last_day, $monthly_payment);

                echo $month_last_day." ".$monthly_payment."<br>";
                $y++;
            }
        }
    }

    public function addPaymentlist($date, $int)
    {
        $sql = 'INSERT INTO payment_records (borrow_id, date_of_payment, amount_to_be_paid)
                    VALUES (:borrow_id, :date_of_payment, :amount_to_be_paid)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':borrow_id', $this->borrow_id);
        $stmt->bindValue(':date_of_payment', $date);
        $stmt->bindValue(':amount_to_be_paid', $int);

        $stmt->execute();
    }
}
