<?php
/**
 * 一致性Hash分布
 * 天涯PHP博客
 * http://blog.phpha.com
 */
class FlexiHash{
    //服务器列表
    private $serverList = array();
    //记录是否已经排序
    private $isSorted = FALSE;
    //添加一台服务器
    public function addServer($server){
        $hash = $this->mHash($server);
        if(!isset($this->serverList[$hash])){
            $this->serverList[$hash] = $server;
        }
        //需要重新排序
        $this->isSorted = FALSE;
        return TRUE;
    }
    //移除一台服务器
    public function removeServer($server){
        $hash = $this->mHash($server);
        if(isset($this->serverList[$hash])){
            unset($this->serverList[$hash]);
        }
        //需要重新排序
        $this->isSorted = FALSE;
        return TRUE;
    }
    //在当前服务器列表查找合适的服务器
    public function lookup($key){
        $hash = $this->mHash($key);
        //先进行倒序排序操作
        if(!$this->isSorted){
            krsort($this->serverList, SORT_NUMERIC);
            $this->isSorted = TRUE;
        }
        //圆环上顺时针方向查找当前KEY紧邻的一台服务器
        foreach($this->serverList as $pos => $server){
            if($hash >= $pos)	return $server;
        }
        //没有找到则返回顺时针方向最后一台服务器
        return $this->serverList[count($this->serverList) - 1];
    }
    //Hash函数
    private function mHash($key){
        $md5 = substr(md5($key), 0, 8);
        $seed = 31;
        $hash = 0;
        for($i = 0; $i < 8; $i++){
            $hash = $hash * $seed + ord($md5{$i});
            $i++;
        }
        return $hash & 0x7FFFFFFF;
    }
}
?>