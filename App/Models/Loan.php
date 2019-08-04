<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class Loan extends \Core\Model
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

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO loan_info (loan_user_id, loan_email, loan_date, loan_amount, loan_payment_months, interestRate, monthlyinterest, overallmonthlyinterest, totalLoan, monthlyPayment)
                    VALUES (:loan_user_id, :loan_email, :loan_date, :loan_amount, 
                    :loan_payment_months, 
                    :interestRate, 
                    :monthlyinterest, 
                    :overallmonthlyinterest, 
                    :totalLoan, 
                    :monthlyPayment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $loaninfo = explode(" - ", $this->loan_by);

            $stmt->bindValue(':loan_user_id', $loaninfo[0], PDO::PARAM_STR);
            $stmt->bindValue(':loan_email', $loaninfo[2], PDO::PARAM_STR);
            $stmt->bindValue(':loan_date', $this->loan_date, PDO::PARAM_STR);
            $stmt->bindValue(':loan_amount', $this->loan_amount, PDO::PARAM_STR);
            $stmt->bindValue(':loan_payment_months', $this->months_to_pay, PDO::PARAM_STR);
            $stmt->bindValue(':interestRate', $this->interestRate, PDO::PARAM_STR);
            $stmt->bindValue(':monthlyinterest', $this->monthlyinterest, PDO::PARAM_STR);
            $stmt->bindValue(':overallmonthlyinterest', $this->overallmonthlyinterest, PDO::PARAM_STR);
            $stmt->bindValue(':totalLoan', $this->totalLoan, PDO::PARAM_STR);
            $stmt->bindValue(':monthlyPayment', $this->monthlyPayment, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function update()
    {
        $this->validate_update();
        
        if (empty($this->errors)) {
            $sql = 'INSERT INTO loan_records (loan_id, date_paid, amount_paid, month_paid, amount_left, month_left)
            VALUES (:loan_id, :date_paid, :amount_paid, :month_paid, :amount_left, :month_left)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':loan_id', $this->loan_id, PDO::PARAM_STR);
            $stmt->bindValue(':date_paid', $this->date_paid, PDO::PARAM_STR);
            $stmt->bindValue(':amount_paid', $this->amount_paid, PDO::PARAM_STR);
            $stmt->bindValue(':month_paid', $this->month_paid, PDO::PARAM_STR);
            $stmt->bindValue(':amount_left', $this->amount_left, PDO::PARAM_STR);
            $stmt->bindValue(':month_left', $this->month_left, PDO::PARAM_STR);
            
            $stmt->execute();

            if ($this->month_left == 0 and $this->amount_left == 0) {
                # code...
                $payment_status = "Fully Paid.";
            }else{
                $payment_status = $this->month_left." month/s and PHP ".$this->amount_left." left";
            }
            
            $sql = 'UPDATE loan_info SET payment_status =:payment_status where id = :loan_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':payment_status', $payment_status, PDO::PARAM_STR);
            $stmt->bindValue(':loan_id', $this->loan_id, PDO::PARAM_STR);
            return $stmt->execute();

        }

        return false;
    }

    public function validate()
    {
        // Name
        if ($this->loan_by == '') {
            $this->errors[] = 'ID - Name - Email is required';
        }

        if ($this->loan_date == '') {
            $this->errors[] = 'Loan date is required';
        }

        if ($this->loan_amount == '') {
            $this->errors[] = 'Loan amount is required';
        }

        if ($this->months_to_pay == '') {
            $this->errors[] = 'Month/s to pay is required';
        }
        
        
    }

    public function validate_update()
    {
        // Name
        if ($this->loan_id == '') {
            $this->errors[] = 'Loan ID is required';
        }

        if ($this->date_paid == '') {
            $this->errors[] = 'Date paid is required';
        }

        if ($this->amount_paid == '') {
            $this->errors[] = 'Amount paid is required';
        }

        if ($this->month_paid == '') {
            $this->errors[] = 'For Month to pay is required';
        }

        
    }

    public static function getAll()
    {
        $sql = 'SELECT * FROM loan_info';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function view($id)
    {
        $sql = 'SELECT * FROM loan_info WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function info($loan_user_id)
    {
        $sql = 'SELECT * FROM loan_info WHERE loan_user_id = :loan_user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':loan_user_id', $loan_user_id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function records($id)
    {
        $sql = 'SELECT * FROM loan_records WHERE loan_id = :loan_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':loan_id', $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function lastRecord($id)
    {
        $sql = 'SELECT * FROM loan_records WHERE loan_id = :loan_id ORDER BY record_id DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':loan_id', $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
    }
}
