<style type="text/css">
 .content ul li{
 	list-style:outside none none;
	float:left;
 	border: solid 1px white;
 	padding:0 10px;
 	margin-bottom:10px;
 	background-color:#e6e6e6;
}
</style>
<nav class="Hui-breadcrumb"><i class="icon-home"></i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span> 配置组 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="data_del('<?=$this->url->get('admin/article/del')?>')" class="btn btn-danger radius"><i class="icon-trash"></i> 批量删除</a> <a class="btn btn-primary radius" onclick="layer_show(750,'','添加配置','<?=$this->url->get('admin/config/add')?>')" href="javascript:;"><i class="icon-plus"></i> 添加配置</a></span> </div>
   <div class="content"> 
		    <ul>
			   <?php foreach($configs as $groups){?>
					  <li lab="<?=$groups['name']?>"><a href="javascript:;"><?=$groups['title']?></a></li>
			   <?php }?>
			</ul>
			<?php foreach($configs as $key=>$groups){?>
				<div lab="<?=$groups['name']?>" <?php if($key!=0){?>style="display:none;clear:both"<?php }?> style="clear:both">
					 <table class="table table-border table-bordered table-bg">
				          <tr>
				            <th >标题：</th>
				            <th >变量名：</th>
				            <th>配置值 ：</th>
				          </tr>
		          
		            	<?php if(count($groups['children'])>0){
		            			foreach($groups['children'] as $group){?>
		            		 <tr>
					            <td><?=$group['title']?></td>
					            <td ><?=$group['name']?></td>
					            <td ><?=$group['value'] ?></td>
		          			</tr>
		            	<?php }}?>
		      		</table>
				</div>
			<?php }?>
	</div>
  <div id="pageNav" class="pageNav"></div>
</div>