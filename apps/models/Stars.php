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


class Stars extends BaseModel
{

    public function getSource()
    {
        return $this->tb_prefix."stars";
    }

    /**
     *
     * @var integer
     */
    public $id;
    /**
     * @var integer
     */
    public $userId;
    /**
     * @SWG\Property(
     *   name="postId",
     *   type="integer",
     *   description="Post ID"
     * )
     * @var integer
     */
    public $articleId;
    /**
     * @SWG\Property(
     *   name="createdAt",
     *   type="integer",
     *   description="Stared time"
     * )
     * @var integer
     */
    public $createdAt = 0;
    protected $tableName = 'blog_stars';
    public $cachePrefix = 'eva_blog_stars_';
    public $cacheTime = 86400;  //一天
    public static $defaultDump = array(
        'id',
        'userId',
        'postId',
        'createdAt',
    );
    public function getCache()
    {
        /** @var \Phalcon\Cache\Backend\Libmemcached $cache */
        $cache =  $this->getDI()->get('modelsCache');
        return $cache;
    }
    public function createCacheKey($params){
        ksort($params);
        $str = $this->cachePrefix;
        foreach($params as $k=>$v){
            $str .= $k.'_'.$v.'_';
        }
        return $str;
    }
    public function refreshCache($params)
    {
        $cacheKey = $this->createCacheKey($params);
        if($this->getCache()->exists($cacheKey)){
            $this->getCache()->delete($cacheKey);
        }
    }
    public function afterSave()
    {
        $this->refreshCache(array('userId'=>$this->userId));
        $this->refreshCache(array('postId'=>$this->postId));
        $this->refreshCache(array('userId'=>$this->userId,'postId'=>$this->postId));
    }
    public function afterDelete()
    {
        $this->refreshCache(array('userId'=>$this->userId));
        $this->refreshCache(array('postId'=>$this->postId));
        $this->refreshCache(array('userId'=>$this->userId,'postId'=>$this->postId));
    }
    public function initialize()
    {
        $this->hasOne('postId', 'Eva\EvaBlog\Entities\Posts', 'id', array(
            'alias' => 'post'
        ));
        $this->hasOne('userId', 'Eva\EvaUser\Entities\Users', 'id', array(
            'alias' => 'user'
        ));
    }
}