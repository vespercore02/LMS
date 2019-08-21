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
            }else {
                # code...
                $sql = 'UPDATE contribution_term_records SET '.$this->column_name.'=:contri WHERE user_id = :user_id';

                $db = static::getDB();
                $stmt = $db->prepare($sql);

                $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
                $stmt->bindValue(':contri', $this->contri, PDO::PARAM_STR);

                return $stmt->execute();
            }
            

        }else{

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
        
        /*
        $sql = 'SELECT * FROM contribution_term_records WHERE user_id in ('.$members['id'].')';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();
        */
    }
    
}
