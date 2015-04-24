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

class ArticleVote extends BaseModel
{
//    public function findVotesByUserId($userId,$commentIds = null)
//    {
//        $builder = $this->getModelsManager()->createBuilder();
//
//        $builder->from('Eva\EvaComment\Entities\Votes');
//
//        $builder->andWhere('userId = :userId:',array('userId'=>$userId));
//        if(is_array($commentIds)){
//            $builder->inWhere('commentId', $commentIds);
//        }
//
//        $votes = $builder->getQuery()->execute();
//        return $votes;
//    }
    public function createVote(Posts $post,$userId,$action)
    {
        $userVote = new VotesUsers();
        $userVote->postId = $post->id;
        $userVote->userId = $userId;
        $userVote->voteType = $action;
        return $userVote;
    }
    public function findVote(Posts $post,$userId,$action)
    {
        return VotesUsers::findFirst("postId='$post->id' AND userId='$userId' AND voteType='$action'");
    }
    public function saveVote(Posts $post,VotesUsers $userVote)
    {
        $action = $userVote->voteType;
        $userVote->save();
        $vote = Votes::findFirst(array("postId = $post->id",'for_update'=>true));
        if (!$vote) {
            $vote = new Votes();
            $action == Votes::TYPE_UP ? $vote->upVote = 1 : $vote->downVote = 1;
        } else
            $vote->upVote++;
        $vote->postId = $post->id;
        $vote->lastVotedAt = time();
        $vote->save();
    }
    public function removeVote(Posts $post,VotesUsers $userVote){
        $action = $userVote->voteType;
        $userVote->delete();
        $vote = Votes::findFirst(array("postId = $post->id",'for_update'=>true));
        if (!$vote) {
            return false; //todo
        }
        if($action == Votes::TYPE_UP){
            if($vote->upVote <= 0){
                $vote->upVote = 0;
            }else{
                $vote->upVote--;
            }
        }else{
            if($vote->downVote <= 0){
                $vote->downVote = 0;
            }else{
                $vote->downVote--;
            }
        }
        $vote->save();
    }
}