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
 * Class Category
 * @package Multiple\Models
 * 分类Model
 */
class Category extends BaseModel
{

    public function getSource()
    {
        return $this->tb_prefix."category";
    }
  
   /* public function columnMap()
    {
        return array(
            'id' => 'id',
            'categoryName' => 'categoryName',
            'slug' => 'slug',
            'description' => 'description',
            'parentId' => 'parentId',
            'rootId' => 'rootId',
            'sortOrder' => 'sortOrder',
            'createdAt' => 'createdAt',
            'count' => 'count',
            'leftId' => 'leftId',
            'rightId' => 'rightId',
            'imageId' => 'imageId',
            'image' => 'image'
        );
    }
    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            'Eva\EvaBlog\Entities\CategoriesPosts',
            'categoryId',
            'postId',
            'Eva\EvaBlog\Entities\Posts',
            'id',
            array('alias' => 'Posts')
        );
    }

    public function beforeValidationOnCreate()
    {
        $this->createdAt = time();
        if (!$this->slug) {
            $this->slug = \Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 8);
        }
    }
    public function beforeCreate()
    {
        if (!$this->parentId) {
            $this->parentId = 0;
            $this->rootId = 0;
        } else {
            $parentCategory = self::findFirst($this->parentId);
            if ($parentCategory) {
                if ($parentCategory->parentId) {
                    $this->rootId = $parentCategory->rootId;
                } else {
                    $this->rootId = $parentCategory->id;
                }
            } else {
                $this->rootId = 0;
            }
        }
    }
    public function beforeUpdate()
    {
        
        //not allow set self to parent
        if (!$this->parentId || $this->parentId == $this->id) {
            $this->parentId = 0;
            $this->rootId = 0;
        } else {
            $parentCategory = self::findFirst($this->parentId);
            if ($parentCategory) {
                if (
                    $parentCategory->rootId == $this->id  //not allow move to child node
                    || $parentCategory->rootId == $this->rootId
                ) {
                    throw new Exception\InvalidArgumentException('ERR_BLOG_CATEGORY_NOT_ALLOW_MOVE');
                } else {
                    if ($parentCategory->parentId) {
                        $this->rootId = $parentCategory->rootId;
                    } else {
                        $this->rootId = $parentCategory->id;
                    }
                }
            } else {
                $this->rootId = 0;
            }
        }
        
    }*/
 /*   public function createCategory()
    {
        if ($this->getDI()->getRequest()->hasFiles()) {
            $upload = new UploadModel();
            $files = $this->getDI()->getRequest()->getUploadedFiles();
            if (!$files) {
                return;
            }
            $file = $files[0];
            $file = $upload->upload($file);
            if ($file) {
                $this->imageId = $file->id;
                $this->image = $file->getFullUrl();
            }
        }
        $this->save();
    }
    public function updateCategory()
    {
        if ($this->getDI()->getRequest()->hasFiles()) {
            $upload = new UploadModel();
            $files = $this->getDI()->getRequest()->getUploadedFiles();
            if (!$files) {
                return;
            }
            $file = $files[0];
            $file = $upload->upload($file);
            if ($file) {
                $this->imageId = $file->id;
                $this->image = $file->getFullUrl();
            }
        }
        $this->save();
    }*/
}