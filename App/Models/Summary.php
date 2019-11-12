<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

class Summary extends \Core\Model
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

    public function update()
    {
        $sql = 'UPDATE summary_records
                SET payment_rcv = :payment_rcv,
                interest_earned = :interest_earned
                WHERE date = :date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':payment_rcv', $this->payment_rcv, PDO::PARAM_STR);
        $stmt->bindValue(':interest_earned', $this->interest_earned, PDO::PARAM_STR);
        
        $stmt->execute();

    }

    public static function getMonthSummaryRecords($contri_date)
    {
        $sql = 'SELECT date, contri_wout_int, amount_borrow, payment_rcv, deficit, interest_earned, est_earned, total
        FROM summary_records
        WHERE date = :contri_date';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':contri_date', $contri_date, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
