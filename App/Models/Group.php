<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

class Group extends \Core\Model
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
     * Add Group information
     *
     * @param string $id
     * @return void
     */
    public static function groupAddInfo($id)
    {
        $sql = 'INSERT INTO group_info(group_id)
        VALUES(:group_id)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group_id', $id, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Update Group Info
     *
     * @return void
     */
    public function groupUpdateInfo()
    {
        $total_contri = self::getTotalContri();
        
        $contri = self::groupCheckInfo();

        if (isset($contri)) {
            # code...
            $sql = 'UPDATE group_info 
            SET group_contributions = :group_contributions
            WHERE group_id = :group_id ';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':group_contributions', $contri + $this->contri, PDO::PARAM_STR);
            $stmt->bindValue(':group_id', $this->group, PDO::PARAM_STR);

            $stmt->execute();
        } else {
            # code...
        }
    }

    /**
     * Check Group Info Contribution
     *
     * @return void contribution
     */
    public function groupCheckInfo()
    {
        $sql = 'SELECT group_id, group_contributions 
        FROM group_info 
        where group_id = :group_id ';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':group_id', $this->group, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            # code...

            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            return $row['group_contributions'];
        }

        return false;
    }

    /**
     * Get Group info
     *
     * @param string $group_id
     * @return void
     */
    public static function getGroupInfo($group_id)
    {
        $sql = 'SELECT * FROM group_info WHERE group_id = :group_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Groups info
     *
     */
    public static function getGroups()
    {
        $sql = 'SELECT * FROM group_info';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Get Group members
    *
    * @return mixed return Group members or nonne
    */
    public static function groupMembers($group_id)
    {
        $sql = 'SELECT id, name, belonging_group FROM users WHERE belonging_group = :group_id ORDER BY id ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function groupContributions($group_id)
    {
        $sql = 'SELECT users.name, contribution_records.contri, contribution_records.contri_date 
        FROM users 
        LEFT JOIN contribution_records ON users.id = contribution_records.user_id
        WHERE contribution_records.belonging_group = :group_id ORDER BY contribution_records.contri_date ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Contributions per month
     *
     * @param string $group_id
     * @param string $month
     * @return void 
     */
    public static function groupContributionsMonth($group_id, $month)
    {
        $sql = 'SELECT users.name, contribution_records.contri, contribution_records.contri_date, 
        contribution_records.total_contri_wout_int, contribution_records.month_int, contribution_records.total_int,
        contribution_records.total_contri_w_int
        FROM users 
        LEFT JOIN contribution_records ON users.id = contribution_records.user_id
        WHERE contribution_records.belonging_group = :group_id 
        AND contribution_records.contri_date = :month
        ORDER BY contribution_records.contri_date ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->bindValue(':month', $month);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add Group Contribution
     *
     * @return void
     */
    public function groupAddContribution()
    {
        if (self::groupCheckContribution()) {
            # code...
            
            return false;
        } else {
            # code...

            $sql = 'INSERT INTO contribution_records (user_id, belonging_group, contri_date, contri)
            VALUES(:user_id, :belonging_group, :contri_date, :contri)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $this->id, PDO::PARAM_STR);
            $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
            $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);
            $stmt->bindValue(':contri', $this->contri, PDO::PARAM_STR);

            return $stmt->execute();
        }
    }

    /**
     * Check member if already Contribute on the set date
     *
     * @return void
     */
    public function groupCheckContribution()
    {
        $sql = 'SELECT * FROM contribution_records WHERE user_id = :user_id and contri_date = :contri_date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':contri_date', $this->month);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            # code...
            return true;
        }

        return false;
    }

    /**
     * Update summary contribution table
     *
     * @return void
     */
    public function summaryUpdateContri()
    {
        $total_contri = self::getTotalContri();

        if (self::summaryCheckContri()) {
            # code...
            $sql = 'UPDATE summary_records 
            SET 	contri_wout_int = :contri_wout_int 
            WHERE date = :date and belonging_group = :belonging_group';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':contri_wout_int', $total_contri, PDO::PARAM_STR);
            $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->month, PDO::PARAM_STR);

            $stmt->execute();
        } else {
            # code...
            $sql = 'INSERT INTO summary_records 
            (contri_wout_int, date, belonging_group) 
            VALUES(:contri_wout_int, :date, :belonging_group)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':contri_wout_int', $total_contri, PDO::PARAM_STR);
            $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->month, PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    /**
     * Check summary Contribution if group already have or not
     *
     * @return void if there is a summary of the said date or the said group else 0
     */
    public function summaryCheckContri()
    {
        $total_contri = self::getTotalContri();

        $sql = 'SELECT date FROM summary_records where date = :date and belonging_group = :belonging_group';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
        $stmt->bindValue(':date', $this->month, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            # code...
            return true;
        }

        return false;
    }

    /**
     * Get Contributions of Group and Total it
     *
     * @return void if there is a contribution else none
     */
    public function getTotalContri()
    {
        $sql = 'SELECT contri FROM contribution_records 
        WHERE belonging_group = :belonging_group 
        and contri_date = :contri_date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':belonging_group', $this->group, PDO::PARAM_STR);
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);

        $stmt->execute();

        $contri = array();

        if ($stmt->rowCount() > 0) {
            # code...
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            for ($i=0; $i < $stmt->rowCount(); $i++) {
                # code...

                $contri[] = $row[$i]['contri'];
            }

            return array_sum($contri);
        }
    }

    
    public static function groupSummaryMonth($group_id, $month)
    {
        $sql = 'SELECT contri_wout_int, amount_borrow, payment_rcv, deficit, interest_earned, est_earned, total
        FROM summary_records
        Where date = :month and belonging_group = :group_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->bindValue(':month', $month);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function groupBorrowMonth($group_id, $month)
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

    
}
