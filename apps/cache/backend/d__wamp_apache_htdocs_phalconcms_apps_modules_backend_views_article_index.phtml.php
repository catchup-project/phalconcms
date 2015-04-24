<nav class="Hui-breadcrumb"><i class="icon-home"></i> 首页 <span class="c-gray en">&gt;</span> 图片库 <span class="c-gray en">&gt;</span> 图片列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="data_del('<?=$this->url->get('admin/article/del')?>')" class="btn btn-danger radius"><i class="icon-trash"></i> 批量删除</a> <a class="btn btn-primary radius" onclick="layer_show(750,'','添加文章','<?=$this->url->get('admin/article/add')?>')" href="javascript:;"><i class="icon-plus"></i> 添加文章</a></span> <span class="r">共有数据：<strong><?=count($articles)?></strong> 条</span> </div>
  <table class="table table-border table-bordered table-bg table-hover table-sort">
    <thead>
      <tr class="text-c">
        <th width="40"><input name="" type="checkbox" value=""></th>
        <th width="80">ID</th>
        <th width="100">频道名称</th>
        <th>排序</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
    <?php ?>
    <?php
    if(count($articles)>0){
	    	foreach($articles as $article){?>
		      <tr class="text-c">
		        <td><input name="" type="checkbox" value="<?=$article['id']?>"></td>
		        <td><?=$article['id']?></td>
		        <td><?=$article['title']?></td>
		        <td><?=$article['sort_order']?></td>
		        <td class="f-14 picture-manage"> <a style="text-decoration:none" class="ml-5" onClick="layer_show(750,'','文章编辑','<?=$this->url->get('admin/article/edit/'.$article['id'])?>')" href="javascript:;" title="编辑"><i class="icon-edit"></i></a> <a style="text-decoration:none" class="ml-5" onClick="one_del(this,'<?=$this->url->get('admin/article/del/'.$article['id'])?>')" href="javascript:;" title="删除"><i class="icon-trash"></i></a></td>
		      </tr>
      <?php }
	}?>
    </tbody>
  </table>
  <div id="pageNav" class="pageNav"></div>
</div>