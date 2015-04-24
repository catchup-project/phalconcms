<?php
namespace Multiple\Library;
/**
    $goods_model=Goods::model();
    //1、获得商品总的记录数目
    $sum=$goods_model->count();
    //定义每页显示条数
    $PageSize=6;
    //2、实例化分页类对象
    $page=new Pagination($sum,$PageSize);
    //3、重新按照分页的样式拼装SQL语句进行查询
    $criteria =new CDbCriteria();
    $criteria->limit = $PageSize;
    $criteria->offset = $page->getPageOffset();
    $goods_infos = $goods_model->findAll($criteria);
    //4、获得分页页码列表(需要传递到视图模版里面显示)
    $page_list=$page->fpage(array(3,4,5,6,7));
    //5、调用视图模版，给模版传递数据
    $this->renderPartial('show',array('goods_infos'=>$goods_infos,'page_list'=>$page_list));
 **/

/**
 * 分页类，以组件形式存在
 * 13-5-15 下午8:26 
 */
class Pagination {
		private $total; //数据表中总记录数
		private $listRows; //每页显示行数
		private $limit;
		private $uri;
		private $pageNum; //页数
		private $config=array('header'=>"个记录", "prev"=>"上一页", "next"=>"下一页", "first"=>"首 页", "last"=>"尾 页");
		private $listNum=8;
		public $offset;
		/*
		 * $total
		 * $listRows
		 */
		public function __construct($total, $listRows=10, $pa=""){
			$this->total=$total;
			$this->listRows=$listRows;
			$this->uri=$this->getUri($pa);
			$this->page=!empty($_GET["page"]) ? $_GET["page"] : 1;
			$this->pageNum=ceil($this->total/$this->listRows);
			$this->limit=$this->setLimit();
		}
	
	        public function getPageOffset(){
	            return ($this->page-1)*$this->listRows;
	        }

		private function setLimit(){
			$this->offset=($this->page-1)*$this->listRows;
			return "Limit ".($this->page-1)*$this->listRows.", {$this->listRows}";
		}

		private function getUri($pa){
			$url=$_SERVER["REQUEST_URI"].(strpos($_SERVER["REQUEST_URI"], '?')?'':"?").$pa;
			$parse=parse_url($url);
			if(isset($parse["query"])){
				parse_str($parse['query'],$params);
				unset($params["page"]);
				$url=$parse['path'].'?'.http_build_query($params);
			}

			return $url;
		}

		function __get($args){
			if($args=="limit")
				return $this->limit;
			else
				return null;
		}

		private function start(){
			if($this->total==0)
				return 0;
			else
				return ($this->page-1)*$this->listRows+1;
		}

		private function end(){
			return min($this->page*$this->listRows,$this->total);
		}

		private function first(){
			if($this->page==1)
				$html='';
			else
				$html="&nbsp;&nbsp;<a href='{$this->uri}&page=1'>{$this->config["first"]}</a>&nbsp;&nbsp;";
			return $html;
		}

		private function prev(){
			if($this->page==1)
				$html='';
			else
				$html="&nbsp;&nbsp;<a href='{$this->uri}&page=".($this->page-1)."'>{$this->config["prev"]}</a>&nbsp;&nbsp;";
			return $html;
		}

		private function pageList(){
			$linkPage="";
			$inum=floor($this->listNum/2);
			for($i=$inum; $i>=1; $i--){
				$page=$this->page-$i;
				if($page<1)
					continue;
				$linkPage.="&nbsp;<a href='{$this->uri}&page={$page}'>{$page}</a>&nbsp;";

			}
			$linkPage.="&nbsp;{$this->page}&nbsp;";
			for($i=1; $i<=$inum; $i++){
				$page=$this->page+$i;
				if($page<=$this->pageNum)
					$linkPage.="&nbsp;<a href='{$this->uri}&page={$page}'>{$page}</a>&nbsp;";
				else
					break;
			}
			return $linkPage;
		}

		private function next(){
			if($this->page==$this->pageNum)
				$html='';
			else
				$html="&nbsp;&nbsp;<a href='{$this->uri}&page=".($this->page+1)."'>{$this->config["next"]}</a>&nbsp;&nbsp;";
			return $html;
		}

		private function last(){
			if($this->page==$this->pageNum)
				$html='';
			else
				$html="&nbsp;&nbsp;<a href='{$this->uri}&page=".($this->pageNum)."'>{$this->config["last"]}</a>&nbsp;&nbsp;";
			return $html;
		}

		private function goPage(){
			return '&nbsp;&nbsp;<input type="text" onkeydown="javascript:if(event.keyCode==13){var page=(this.value>'.$this->pageNum.')?'.$this->pageNum.':this.value;location=\''.$this->uri.'&page=\'+page+\'\'}" value="'.$this->page.'" style="width:25px"><input type="button" value="GO" onclick="javascript:var page=(this.previousSibling.value>'.$this->pageNum.')?'.$this->pageNum.':this.previousSibling.value;location=\''.$this->uri.'&page=\'+page+\'\'">&nbsp;&nbsp;';
		}
		public function fpage($display=array(0,1,2,3,4,5,6,7,8)){
			$html[0]="&nbsp;&nbsp;共有<b>{$this->total}</b>{$this->config["header"]}&nbsp;&nbsp;";
			$html[1]="&nbsp;&nbsp;每页显示<b>".($this->end()-$this->start()+1)."</b>条，本页<b>{$this->start()}-{$this->end()}</b>条&nbsp;&nbsp;";
			$html[2]="&nbsp;&nbsp;<b>{$this->page}/{$this->pageNum}</b>页&nbsp;&nbsp;";
			$html[3]=$this->first();
			$html[4]=$this->prev();
			$html[5]=$this->pageList();
			$html[6]=$this->next();
			$html[7]=$this->last();
			$html[8]=$this->goPage();
			$fpage='';
			foreach($display as $index){
				$fpage.=$html[$index];
			}
			return $fpage;
		}
	}