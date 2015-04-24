<?php
namespace Multiple\Models;

use Phalcon\Mvc\Model\Validator\InclusionIn;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\Exclusionin;
use Phalcon\Mvc\Model\Validator\Numericality;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Validator\Url;
use Phalcon\Mvc\Model\Behavior\SoftDelete;//软删除;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Mvc\Model\Relation;
use Phalcon\Db\Column;


/**
 * Multiple\Models\Users
 * All the users registered in the application
 */
class User extends BaseModel
{
	
	
	public function getSource()
	{	
		return $this->tb_prefix."user";
	}
	public function beforeSave(){
		$this->addtime=time();
	}
    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
	$this->status=1;
	$this->addtime=time();
     /*   if (empty($this->password)) {

            // Generate a plain temporary password
            $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));

            // The user must change its password in first login
            $this->mustChangePassword = 'Y';

            // Use this password as default
            $this->password = $this->getDI()
                ->getSecurity()
                ->hash($tempPassword);
        } else {
            // The user must not change its password in first login
            $this->mustChangePassword = 'N';
        }

        // The account must be confirmed via e-mail
        $this->active = 'N';

        // The account is not suspended by default
        $this->suspended = 'N';

        // The account is not banned by default
        $this->banned = 'N';*/
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
   /*public function afterSave()
    {
        if ($this->active == 'N') {

            $emailConfirmation = new EmailConfirmations();

            $emailConfirmation->usersId = $this->id;

            if ($emailConfirmation->save()) {
                $this->getDI()
                    ->getFlash()
                    ->notice('A confirmation mail has been sent to ' . $this->email);
            }
        }
    }*/

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

   /* public function initialize()
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

        $this->hasMany('id', 'Multiple\Models\PasswordChanges', 'usersId', array(
            'alias' => 'passwordChanges',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));

        $this->hasMany('id', 'Multiple\Models\ResetPasswords', 'usersId', array(
            'alias' => 'resetPasswords',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));
    }*/
}
