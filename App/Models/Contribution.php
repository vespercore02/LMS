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

    public function save()
    {
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

?>