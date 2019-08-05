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
     * Get Group members
     *
     * @return mixed return Group members or nonne
     */
    public static function groupMembers()
    {
        $sql = 'SELECT * FROM groups_records where groups_id = :groups_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
