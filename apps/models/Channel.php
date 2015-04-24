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
 * Class Post
 * @package Multiple\Models
 */
class Channel extends BaseModel
{

    public function getSource()
    {
        return $this->tb_prefix."channel";
    }
  
}
