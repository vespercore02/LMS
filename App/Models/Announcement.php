<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

/**
 * Announcement model
 *
 * PHP version 7.0
 */
class Announcement extends \Core\Model
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
     * Save the annoucement model with the current property values
     * 
     * 
     */

    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $sql = 'INSERT INTO annoucements (date, title, message, created_by, created_by_id)
            VALUES (:date, :title, :message, :created_by, :created_by_id)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':title', $this->announcement_title, PDO::PARAM_STR);
            $stmt->bindValue(':message', $this->announcement_message, PDO::PARAM_STR);
            $stmt->bindValue(':created_by', $this->created_by, PDO::PARAM_STR);
            $stmt->bindValue(':created_by_id', $this->created_by_id, PDO::PARAM_INT);
            
            return $stmt->execute();
        }

        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Title
        if ($this->announcement_title == '') {
            $this->errors[] = 'Title is required';
        }

        // Message
        if ($this->announcement_message == '') {
            $this->errors[] = 'Message is required';
        }
    }


    /**
     * Load annoucement information
     * 
     * @return mixed announcement object if found, false otherwise
     */

    public static function load()
    {
        $sql = 'SELECT * FROM annoucements ORDER BY date DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
