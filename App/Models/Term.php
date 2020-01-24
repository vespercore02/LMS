<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;
use Datetime;

class Term extends \Core\Model
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

    public static function save($term, $month_start)
    {
        $sql = 'INSERT INTO terms (term, month_start)
                VALUES(:term, :month_start)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':term', $term);
        $stmt->bindValue(':month_start', $month_start);

        $stmt->execute();
    }

    public function view()
    {
        $sql = 'SELECT * FROM terms GROUP BY term';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function months($term)
    {
        $sql = 'SELECT month_start FROM terms WHERE term=:term ORDER BY month_start ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':term', $term);
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function term($month)
    {
        $sql = 'SELECT term FROM terms WHERE month_start=:month_start';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':month_start', $month);
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
