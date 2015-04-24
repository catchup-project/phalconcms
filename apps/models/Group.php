<?php
namespace Multiple\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Multiple\Models\Users
 * All the users registered in the application
 */
class Group extends Model
{

    const DELETED = '0';

    const NOT_DELETED = '1';

    public function getSource()
    {
        return $this->tb_prefix."group";
    }
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;



    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {

    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {

    }

    /**
     * Validate that emails are unique across users
     */
    public function validation()
    {
        $this->validate(new Uniqueness(array(
            "field" => "email",
            "message" => "The email is already registered"
        )));

        return $this->validationHasFailed() != true;
    }

    public function initialize()
    {
        $this->belongsTo('profilesId', 'Multiple\Models\Profiles', 'id', array(
            'alias' => 'profile',
            'reusable' => true
        ));

        $this->hasMany('id', 'Multiple\Models\SuccessLogins', 'usersId', array(
            'alias' => 'successLogins',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));
    }
}
