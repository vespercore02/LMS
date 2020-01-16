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

    public function update()
    {
        $sql = 'UPDATE borrow_records 
                SET payment = :payment,
                remaining = :remaining,
                int_acquired = :int_acquired
                WHERE id = :id';
                
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':id', $this->borrow_id);
        $stmt->bindValue(':payment', $this->payment);
        $stmt->bindValue(':remaining', $this->remaining);
        $stmt->bindValue(':int_acquired', $this->int_acquired);

        $stmt->execute();
    }

    public static function viewMember($user_id)
    {
        $sql = 'SELECT * FROM borrow_records WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':user_id', $user_id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateSummaryBorrow()
    {
        $total_Amount_Borrow = self::getTotalAmountBorrow();
        $total_Amount_Remainig = self::getTotalAmountRemaining();

        if (self::checkSummaryBorrow()) {
            # code...
            # if belonging group are needed NA! or kung kailangan na
            /*
            $sql = 'UPDATE summary_records 
            SET 	amount_borrow = :amount_borrow, deficit = :deficit 
            WHERE date = :date and belonging_group = :belonging_group';
            */
            $sql = 'UPDATE summary_records 
            SET 	amount_borrow = :amount_borrow, deficit = :deficit 
            WHERE date = :date ';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':amount_borrow', $total_Amount_Borrow, PDO::PARAM_STR);
            $stmt->bindValue(':deficit', $total_Amount_Remainig, PDO::PARAM_STR);
            //$stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->cut_off);

            $stmt->execute();
        } else {
            # code...
            # if belonging group are needed NA! or kung kailangan na
            /*
            $sql = 'INSERT INTO summary_records 
            (amount_borrow, deficit, date, belonging_group) 
            VALUES(:amount_borrow, :deficit, :date, :belonging_group)';
            */
            $sql = 'INSERT INTO summary_records 
            (amount_borrow, deficit, date) 
            VALUES(:amount_borrow, :deficit, :date)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':amount_borrow', $total_Amount_Borrow, PDO::PARAM_STR);
            $stmt->bindValue(':deficit', $total_Amount_Remainig, PDO::PARAM_STR);
            //$stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->cut_off, PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    public function checkSummaryBorrow()
    {
        $sql = 'SELECT amount_borrow 
                FROM summary_records
                WHERE date = :date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':date', $this->cut_off);

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
        $sql = 'SELECT users.name, 
        borrow_records.principal, 
        borrow_records.date_borrow,
        borrow_records.payment, 
        borrow_records.remaining, 
        borrow_records.int_acquired
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
    

    public static function getMonthBorrowRecords($date)
    {
        $sql = 'SELECT users.id, 
        users.name, 
        users.belonging_group,  
        borrow_records.id as borrow_records_id, 
        borrow_records.date_borrow, 
        borrow_records.principal, 
        borrow_records.payment, 
        borrow_records.remaining, 
        borrow_records.int_acquired 
       FROM users left join borrow_records 
       on users.id = borrow_records.user_id 
       where borrow_records.date = :date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function borrowInfo($id)
    {
        $sql = 'SELECT * FROM borrow_records WHERE id = :borrow_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':borrow_id', $id);

        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function viewTerm($term)
    {
        $sql = 'SELECT * FROM borrow_records WHERE term_id = :term ORDER BY date_borrow ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':term', $term);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
