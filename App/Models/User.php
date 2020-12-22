<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
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
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users (name, email, password_hash, activation_hash, access_rights, belonging_group)
                    VALUES (:name, :email, :password_hash, :activation_hash, :access_rights, :belonging_group)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);
            $stmt->bindValue(':access_rights', $this->member_access, PDO::PARAM_STR);
            $stmt->bindValue(':belonging_group', $this->member_group, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function updatePass()
    {
        $password_hash = password_hash($this->new_password, PASSWORD_DEFAULT);

        $token = new Token();
        $hashed_token = $token->getHash();
        $this->activation_token = $token->getValue();

        $sql = 'UPDATE users
                    SET password_hash = :password_hash
                    WHERE id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $this->user_id);
        $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public static function delete($id)
    {
        $sql = 'DELETE FROM users
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Name
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }
        if (static::emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'email already taken';
        }

        // Password
        if (strlen($this->password) < 6) {
            $this->errors[] = 'Please enter at least 6 characters for the password';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one letter';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one number';
        }
    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     * @param string $ignore_id Return false anyway if the record found has this ID
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email, $ignore_id = null)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT id, email, is_active, password_hash FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Search a user model by email address
     *
     * @param string $id id to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function searchById($id)
    {
        $sql = 'SELECT * FROM users WHERE id like :id or name like :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', '%'.$id.'%', PDO::PARAM_STR);

        //$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Authenticate a user by email and password.
     *
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user && $user->is_active) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT id, name, email, access_rights, belonging_group FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @return boolean  True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions to the user specified
     *
     * @param string $email The email address
     *
     * @return void
     */
    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->startPasswordReset()) {
                $user->sendPasswordResetEmail();
            }
        }
    }

    /**
     * Start the password reset process by generating a new token and expiry
     *
     * @return void
     */
    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2;  // 2 hours from now

        $sql = 'UPDATE users
                SET password_reset_hash = :token_hash,
                    password_reset_expires_at = :expires_at
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions in an email to the user
     *
     * @return void
     */
    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('Password/reset_email.txt', ['url' => $url]);
        $html = View::getTemplate('Password/reset_email.html', ['url' => $url]);

        Mail::send($this->email, 'Password reset', $text, $html);
    }

    /**
     * Find a user model by password reset token and expiry
     *
     * @param string $token Password reset token sent to user
     *
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {

            // Check password reset token hasn't expired
            if (strtotime($user->password_reset_expires_at) > time()) {
                return $user;
            }
        }
    }

    /**
     * Reset the password
     *
     * @param string $password The new password
     *
     * @return boolean  True if the password was updated successfully, false otherwise
     */
    public function resetPassword($password)
    {
        $this->password = $password;

        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users
                    SET password_hash = :password_hash,
                        password_reset_hash = NULL,
                        password_reset_expires_at = NULL
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Send an email to the user containing activation link
     *
     * @return void
     */
    public function sendActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

        $text = View::getTemplate('Signup/activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Signup/activation_email.html', ['url' => $url]);

        Mail::send($this->email, 'Account Activation', $text, $html);
    }

    /**
     * Return activation link
     *
     * @return void
     */
    public function returnActivationLink()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

        return $url;
    }

    /**
     *
     *
     *
     */
    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Get Members info
     *
     */
    public static function getMembers()
    {
        $sql = 'SELECT id, name, email, access_rights, belonging_group FROM users';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }
    /**
     * Get Members info
     *
     */
    public static function getMembersRange($page)
    {
        $per_page = 10;

        $start    = ($page - 1) * $per_page;

        $sql = 'SELECT id, name, email, access_rights, belonging_group 
                FROM users
                LIMIT '. $start .', '.$per_page.'';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Members Count
     *
     */
    public static function getMemberCount()
    {
        $sql = 'SELECT id FROM users';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public static function editMember()
    {
        $sql = 'UPDATE users
                SET ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();
    }

    /**
     * Get Access Rights info
     *
     */
    public static function getAccessRights()
    {
        $sql = 'SELECT * FROM access_rights';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Save Access Rights info
     *
     */
    public function saveAccessRights()
    {
        $sql = 'INSERT INTO access_rights (access_rights_id, access_name, description )
        VALUES (:access_rights_id, :access_name, :description )';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':access_rights_id', $this->access_number, PDO::PARAM_STR);
        $stmt->bindValue(':access_name', $this->access_name, PDO::PARAM_STR);
        $stmt->bindValue(':description', $this->access_description, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * SET Access Rights of Member
     */
    public static function setAccessRights()
    {
        $sql = 'UPDATE users
                SET access_rights = :access_rights
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':access_rights', $this->access_rights, PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_STR);

        $stmt->execute();
    }

    

    /**
     * Save Account Group info
     *
     */
    public function saveGroups()
    {
        $sql = 'INSERT INTO account_groups (group_name )
        VALUES (:group_name)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':group_name', $this->group_name, PDO::PARAM_STR);

        $stmt->execute();

        return $db->lastInsertId();
    }

    /**
     * SET Group of Member
     */
    public static function setGroups()
    {
        $sql = 'UPDATE users
                SET belonging_group = :belonging_group
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':belonging_group', $this->belonging_group, PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * GET Group Members
     *
     * @return mixed list of member or none
     */
    public static function getGroupMembers($groupId, $id)
    {
        $sql = 'SELECT id, name FROM users WHERE belonging_group = :belonging_group and id != :id ORDER BY id ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':belonging_group', $groupId, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
