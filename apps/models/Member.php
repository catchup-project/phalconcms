<?php

namespace Multiple\Models;
use Phalcon\Db\Column;
use Phalcon\Mvc\Model\Behavior\SoftDelete;//软删除

use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model\Validator\PresenceOf as EmptyValidator;
use Phalcon\Mvc\Model\Behavior\Timestampable;

use Phalcon\Mvc\Model\Relation;

/**
 * 数据验证
 */
use Phalcon\Mvc\Model\Validator\InclusionIn;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\Exclusionin;
use Phalcon\Mvc\Model\Validator\Numericality;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Validator\Url;


class Member extends BaseModel
{

    public function getSource()
    {	
        return $this->tb_prefix."member";
    }
    public function beforeValidationOnCreate()
    {
    	$this->status=1;
    	$this->create_time=time();
    }

    public static function findFirstByEmail($email){
        return self::findFirst("email = '".$email."'");
    }

    public static function findFirstByUsername($name){
        return self::findFirst("username = '".$name."'");
    }

    public static function findFirstById($id){
        return self::findFirst("id = '".$id."'");
    }


    /**
     * @return bool
     */
    /*
    public function validation()
    {
        $this->validate(new EmptyValidator(array(
            'field'   => 'username',
            'message' => 'Пожалуйста, укажите Ваше имя'
        )));

        $this->validate(new EmptyValidator(array(
            'field'   => 'email',
            'message' => 'Пожалуйста, укажите корректный адрес электронной почты'
        )));

        $this->validate(new EmailValidator(array(
            'field'   => 'email',
            'message' => 'Пожалуйста, проверьте корректность указанного адреса электронной почты'
        )));

        $this->validate(new EmptyValidator(array(
            'field'   => 'password',
            'message' => 'Необходимо ввести пароль'
        )));

        if (empty($this->id)) {
            $this->validate(new UniquenessValidator(array(
                'field'   => 'email',
                'message' => 'На этот почтовый адрес уже зарегистрирована учётная запись.'
            )));
        } else {
            $this->validate(new UniquenessValidator(array(
                'field'   => 'email',
                'message' => 'На этот почтовый адрес уже зарегистрирована учётная запись'
            )));
        }

        return $this->validationHasFailed() != true;
    }
    */

}
