<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

class Contribution extends \Core\Model
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
     * Save Contribution of a Member
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        if (self::saveTermRecord()) {
            # code...
            $sql = 'INSERT INTO contribution_records (user_id, contri_date, contri, contri_esti_earns, contri_act_earns, contri_plus_earns)
        VALUES (:user_id, :contri_date, :contri, :contri_esti_earns, :contri_act_earns, :contri_plus_earns)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
            $stmt->bindValue(':contri_date', $this->contri_date, PDO::PARAM_STR);
            $stmt->bindValue(':contri', $this->contri, PDO::PARAM_STR);
            $stmt->bindValue(':contri_esti_earns', $this->contri_esti_earns, PDO::PARAM_STR);
            $stmt->bindValue(':contri_act_earns', $this->contri_act_earns, PDO::PARAM_STR);
            $stmt->bindValue(':contri_plus_earns', $this->contri_plus_earns, PDO::PARAM_STR);

            return $stmt->execute();
        }
    }

    public function add()
    {
        $sql = 'INSERT INTO contribution_records (user_id, belonging_group, term_id, contri_date, contri, total_contri_wout_int, month_int, total_int, total_contri_w_int)
        VALUES (:user_id, :belonging_group, :term_id, :contri_date, :contri, :total_contri_wout_int, :month_int, :total_int, :total_contri_w_int)';

        //$sql = 'INSERT INTO contribution_records (user_id, belonging_group, term_id, contri_date, contri)
        //VALUES (:user_id, :belonging_group, :term_id, :contri_date, :contri)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':belonging_group', $this->belonging_group, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);
        $stmt->bindValue(':contri', $this->contri, PDO::PARAM_STR);
        $stmt->bindValue(':total_contri_wout_int', $this->total_contri_wout_int, PDO::PARAM_STR);
        $stmt->bindValue(':month_int', $this->month_int, PDO::PARAM_STR);
        $stmt->bindValue(':total_int', $this->total_int, PDO::PARAM_STR);
        $stmt->bindValue(':total_contri_w_int', $this->total_contri_w_int, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function addContri()
    {
        $sql = 'INSERT INTO contribution_records (user_id, belonging_group, term_id, contri_date, contri)
        VALUES (:user_id, :belonging_group, :term_id, :contri_date, :contri)';

        //$sql = 'INSERT INTO contribution_records (user_id, belonging_group, term_id, contri_date, contri)
        //VALUES (:user_id, :belonging_group, :term_id, :contri_date, :contri)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':belonging_group', $this->belonging_group, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);
        $stmt->bindValue(':contri', $this->contri, PDO::PARAM_STR);

        return $stmt->execute();
    }

    
    public function update()
    {
        $sql = 'UPDATE contribution_records
                SET contri = :contri,
                total_contri_wout_int = :total_contri_wout_int,
                total_contri_w_int = :total_contri_w_int
                WHERE contri_date = :contri_date
                AND user_id = :user_id
                AND term_id = :term_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);
        $stmt->bindValue(':contri', $this->contri, PDO::PARAM_STR);
        $stmt->bindValue(':total_contri_wout_int', $this->total_contri_wout_int, PDO::PARAM_STR);
        $stmt->bindValue(':total_contri_w_int', $this->total_contri_w_int, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function updateTotalContriWoutInt()
    {
        $sql = 'UPDATE contribution_records
                SET total_contri_wout_int = :total_contri_wout_int
                WHERE contri_date = :contri_date
                AND user_id = :user_id
                AND term_id = :term_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);
        $stmt->bindValue(':total_contri_wout_int', $this->total_contri_wout_int, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function update_total()
    {
        $sql = 'UPDATE contribution_records
                SET total_contri_wout_int = :total_contri_wout_int,
                total_contri_w_int = :total_contri_w_int
                WHERE contri_date = :contri_date
                AND user_id = :user_id
                AND term_id = :term_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);
        $stmt->bindValue(':total_contri_wout_int', $this->total_contri_wout_int, PDO::PARAM_STR);
        $stmt->bindValue(':total_contri_w_int', $this->total_contri_w_int, PDO::PARAM_STR);

        $stmt->execute();
    }
    
    public function update_month_int()
    {
        $sql = 'UPDATE contribution_records
                SET month_int = :month_int
                WHERE contribution_id = :contribution_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':contribution_id', $this->contribution_id, PDO::PARAM_STR);
        $stmt->bindValue(':month_int', $this->month_int, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function update_total_int()
    {
        $sql = 'UPDATE contribution_records
                SET total_int = :total_int,
                total_contri_w_int = :total_contri_w_int
                WHERE contribution_id = :contribution_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':contribution_id', $this->contribution_id, PDO::PARAM_STR);
        $stmt->bindValue(':total_int', $this->total_int, PDO::PARAM_STR);
        $stmt->bindValue(':total_contri_w_int', $this->total_contri_w_int, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function checkContriDate()
    {
        $sql = 'SELECT contri_date FROM contribution_records WHERE user_id = :user_id and term_id = :term_id and contri_date = :contri_date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public static function getContriInfo($contribution_id)
    {
        $sql = 'SELECT * FROM contribution_records WHERE contribution_id = :contribution_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':contribution_id', $contribution_id, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getContriDate()
    {
        $sql = 'SELECT * FROM contribution_records WHERE user_id = :user_id and term_id = :term_id and contri_date = :contri_date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllContriDate()
    {
        $sql = 'SELECT * FROM contribution_records WHERE contri_date = :contri_date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getLastContri()
    {
        $sql = 'SELECT * FROM contribution_records WHERE user_id = :user_id and term_id = :term_id ORDER BY contribution_id DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getContributedDate()
    {
        $sql = 'SELECT contri_date FROM contribution_records WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);

        $stmt->execute();

        $dates = array();
        if ($stmt->rowCount() > 0) {
            $allRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($allRows as $row) {
                # code...
                $dates[] = $row['contri_date'];
            }

            return $dates;

        }

        return false;
    }

    public function getAllContributedDate()
    {
        $sql = 'SELECT * FROM contribution_records WHERE user_id = :user_id and term_id = :term_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
        $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function view($date)
    {
        $sql = 'SELECT * FROM contribution_records WHERE contri_date = :contri_date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':contri_date', $date);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function viewMember($user_id)
    {
        $sql = 'SELECT * FROM contribution_records WHERE user_id = :user_id order by term_id ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':user_id', $user_id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function viewMemberTerm($user_id, $term_id)
    {
        $sql = 'SELECT * FROM contribution_records WHERE user_id = :user_id and term_id = :term_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':term_id', $term_id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function viewMemberTermContri($user_id)
    {
        $sql = 'SELECT * FROM contribution_records WHERE user_id = :user_id group by term_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':user_id', $user_id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function viewMemberTotalContri($user_id)
    {
        $sql = 'SELECT SUM(contri) as total_contri, 
        SUM(month_int) as total_month_int, 
        total_contri_w_int 
        FROM contribution_records 
        WHERE user_id = :user_id
        ORDER BY contribution_id ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':user_id', $user_id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function viewGroupTotalContri($belonging_group)
    {
        $sql = 'SELECT SUM(contri) as total_contri, 
        SUM(month_int) as total_month_int
        FROM contribution_records 
        WHERE belonging_group = :belonging_group';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':belonging_group', $belonging_group);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function viewTerm($term)
    {
        $sql = 'SELECT * FROM contribution_records WHERE term_id = :term ORDER BY contri_date ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':term', $term);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *
     * Update saved records of member on term_records database
     *
     *
     */
    public function saveTermRecord()
    {
        if (self::checkTermRecord()) {
            # code...
            
            if (self::checkTermColumnRecord()) {
                # code...
                return false;
            } else {
                # code...
                $sql = 'UPDATE contribution_term_records SET '.$this->column_name.'=:contri WHERE user_id = :user_id';

                $db = static::getDB();
                $stmt = $db->prepare($sql);

                $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
                $stmt->bindValue(':contri', $this->contri, PDO::PARAM_STR);

                return $stmt->execute();
            }
        } else {
            $sql = 'INSERT INTO contribution_term_records (user_id, '.$this->column_name.', belonging_group)
            VALUES (:user_id, :contri, :belonging_group)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
            $stmt->bindValue(':contri', $this->contri, PDO::PARAM_STR);
            $stmt->bindValue(':belonging_group', $this->belonging_group, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     *
     * Check term record of member is exist
     *
     * @return mixed User object if found, false otherwise
     */

    public function checkTermRecord()
    {
        
        //echo $this->contri_date;

        $sql = 'SELECT * FROM contribution_term_records WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function checkTermColumnRecord()
    {
        
        //echo $this->contri_date;

        $sql = 'SELECT '.$this->column_name.' FROM contribution_term_records WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);

        $stmt->execute();

        $date =  $stmt->fetch(PDO::FETCH_ASSOC);

        return $date[$this->column_name];
    }

    /**
     * View Contribution of a Member
     *
     * @param $id id to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function records($id)
    {
        $sql = 'SELECT * FROM contribution_records WHERE user_id like :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Get Month Contri
     */

    public static function getMonthContriRecords($contri_date)
    {
        $sql = 'SELECT users.id, users.name, users.belonging_group, contribution_records.contribution_id, contribution_records.contri, contribution_records.contri_date,
        contribution_records.total_contri_wout_int, contribution_records.month_int, contribution_records.total_int, contribution_records.total_contri_w_int
       FROM users left join contribution_records 
       on users.id = contribution_records.user_id 
       where contribution_records.contri_date = :contri_date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':contri_date', $contri_date, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * GET Group Members contribution
     *
     * @return mixed list of member or none
     */
    public static function getGroupMembersContri($groupId)
    {
        $sql = 'SELECT * FROM contribution_term_records WHERE belonging_group = :belonging_group ORDER BY user_id ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':belonging_group', $groupId, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * GET Members Contribution Records per group and month
     *
     *
     */
    public static function getMembersContri($groupId)
    {
        $sql = 'SELECT users.id, users.name, users.belonging_group, contribution_records.contri, contribution_records.contri_date 
        FROM users left join contribution_records 
        on users.id = contribution_records.user_id 
        where contribution_records.belonging_group =:belonging_group
        ORDER BY contribution_records.contri_date ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':belonging_group', $groupId, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * GET Members Contribution Records per group and month
     *
     *
     */
    public static function getGroupMonthlyContri($month, $group)
    {
        $sql = 'SELECT users.id, users.name, users.belonging_group, contribution_records.contri, contribution_records.contri_date,
         contribution_records.contri_esti_earns, contribution_records.contri_act_earns, contribution_records.contri_plus_earns
        FROM users left join contribution_records 
        on users.id = contribution_records.user_id 
        where contribution_records.belonging_group =:group and contribution_records.contri_date = :month
        ORDER BY contribution_records.contri_date ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group', $group, PDO::PARAM_STR);
        $stmt->bindValue(':month', $month, PDO::PARAM_STR);

        $stmt->execute();

        //return $stmt->rowCount();

        $contri = array();

        if ($stmt->rowCount() > 0) {
            # code...

            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            for ($i=0; $i < $stmt->rowCount(); $i++) {
                # code...
                $contri[] = array($row[$i]['name'],
                                    $row[$i]['contri'],
                                    $row[$i]['contri_esti_earns'],
                                    $row[$i]['contri_act_earns'],
                                    $row[$i]['contri_plus_earns']);
            }
            
            return $contri;
        }
    }

    public function updateSummaryContri()
    {
        $total_Contri = self::getTotalContri();

        if (self::checkSummaryContri()) {
            # code...
            $sql = 'UPDATE summary_records 
            SET 	contri_wout_int = :contri_wout_int
            WHERE date = :date and term_id = :term_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':contri_wout_int', $total_Contri, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->month);
            $stmt->bindValue(':term_id', $this->term);

            $stmt->execute();
        } else {
            # code...
            $sql = 'INSERT INTO summary_records 
            (contri_wout_int, date, term_id) 
            VALUES(:contri_wout_int, :date, :term_id)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':contri_wout_int', $this->contri, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->month, PDO::PARAM_STR);
            $stmt->bindValue(':term_id', $this->term, PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    public function checkSummaryContri()
    {
        $sql = 'SELECT contri_wout_int 
                FROM summary_records
                WHERE date = :date and term_id = :term';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':date', $this->month);
        $stmt->bindValue(':term', $this->term);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            # code...
            return true;
        }

        return false;
    }

    public function getTotalContri()
    {
        $sql = 'SELECT contri FROM contribution_records 
        WHERE contri_date = :contri_date and term_id = :term';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':contri_date', $this->month, PDO::PARAM_STR);
        $stmt->bindValue(':term', $this->term);

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
}
