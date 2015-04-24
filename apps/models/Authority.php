<?php

namespace Multiple\Models;
use \Phalcon\Db\Column;
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


class Authority extends BaseModel
{
    public function getSource()
    {
        return $this->tb_prefix."authority";
    }

    public $id;
    public $buttons;
    public $checked;
    public $expanded;
    public $icon_cls;
    public $leaf;
    public $menu_code;
    public $menu_config;
    public $menu_name;
    public $patient_id;
    public $sort_order;
    public $url;
    public function initialize()
    {
        parent::initialize();
    }
}
