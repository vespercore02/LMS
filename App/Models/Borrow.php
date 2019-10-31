<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;
use Datetime;

/**
 * User model
 *
 * PHP version 7.0
 */
class Borrow extends \Core\Model
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


    public function save()
    {

        $sql = 'INSERT INTO borrow_records (user_id, belonging_group, date, date_borrow, principal, remaining, interest_rate, months_to_pay)
                VALUES(:user_id, :belonging_group, :date, :date_borrow, :principal, :remaining, :interest_rate, :months_to_pay)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':belonging_group', $this->group);
        $stmt->bindValue(':date', $this->cut_off);
        $stmt->bindValue(':date_borrow', $this->borrow_date);
        $stmt->bindValue(':principal', $this->borrow_amount);
        $stmt->bindValue(':remaining', $this->remaining);
        $stmt->bindValue(':interest_rate', $this->borrow_interest);
        $stmt->bindValue(':months_to_pay', $this->months_to_pay);

        $stmt->execute();

        return $db->lastInsertId();
    }


    public function updateSummaryBorrow()
    {
        $total_Amount_Borrow = self::getTotalAmountBorrow();
        $total_Amount_Remainig = self::getTotalAmountRemaining();

        if (self::checkSummaryBorrow()) {
            # code...
            $sql = 'UPDATE summary_records 
            SET 	amount_borrow = :amount_borrow, deficit = :deficit 
            WHERE date = :date and belonging_group = :belonging_group';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':amount_borrow', $total_Amount_Borrow, PDO::PARAM_STR);
            $stmt->bindValue(':deficit', $total_Amount_Remainig, PDO::PARAM_STR);
            $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->cut_off);

            $stmt->execute();
        } else {
            # code...
            $sql = 'INSERT INTO summary_records 
            (amount_borrow, deficit, date, belonging_group) 
            VALUES(:amount_borrow, :deficit, :date, :belonging_group)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':amount_borrow', $total_Amount_Borrow, PDO::PARAM_STR);
            $stmt->bindValue(':deficit', $total_Amount_Remainig, PDO::PARAM_STR);
            $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->cut_off, PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    public function checkSummaryBorrow()
    {

        $sql = 'SELECT amount_borrow 
                FROM summary_records
                WHERE date = :date
                AND belonging_group = :belonging_group';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':date', $this->cut_off);
        $stmt->bindValue(':belonging_group', $this->group);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            # code...
            return true;
        }

        return false;
    }

    public function getTotalAmountBorrow()
    {

        $sql = 'SELECT principal FROM borrow_records 
        WHERE belonging_group = :belonging_group 
        and date = :date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->cut_off, PDO::PARAM_STR);

        $stmt->execute();

        $principal = array();

        if ($stmt->rowCount() > 0) {
            # code...
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            for ($i=0; $i < $stmt->rowCount(); $i++) {
                # code...

                $principal[] = $row[$i]['principal'];
            }

            return array_sum($principal);
        }
    }

    public function getTotalAmountRemaining()
    {

        $sql = 'SELECT remaining FROM borrow_records 
        WHERE belonging_group = :belonging_group 
        and date = :date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->cut_off, PDO::PARAM_STR);

        $stmt->execute();

        $remaining = array();

        if ($stmt->rowCount() > 0) {
            # code...
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            for ($i=0; $i < $stmt->rowCount(); $i++) {
                # code...

                $remaining[] = $row[$i]['remaining'];
            }

            return array_sum($remaining);
        }
    }

    public static function borrowList($group_id, $month)
    {
        $sql = 'SELECT users.name, borrow_records.principal, borrow_records.payment, borrow_records.remaining, borrow_records.int_acquired
        FROM users 
        LEFT JOIN borrow_records ON users.id = borrow_records.user_id
        WHERE borrow_records.belonging_group = :group_id 
        AND borrow_records.date = :month
        ORDER BY borrow_records.date ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->bindValue(':month', $month);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    
    
}
