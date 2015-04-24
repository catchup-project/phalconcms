<nav class="Hui-breadcrumb"><i class="icon-home"></i> 首页 <span class="c-gray en">&gt;</span> 图片库 <span class="c-gray en">&gt;</span> 图片列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="icon-trash"></i> 批量删除</a> <a class="btn btn-primary radius" onclick="channel_add('','','添加频道','<?=$this->url->get('admin/channel/add')?>')" href="javascript:;"><i class="icon-plus"></i> 添加频道</a></span> <span class="r">共有数据：<strong><?=count($channels)?></strong> 条</span> </div>
  <table class="table table-border table-bordered table-bg table-hover table-sort">
    <thead>
      <tr class="text-c">
        <th width="40"><input name="" type="checkbox" value=""></th>
        <th width="80">ID</th>
        <th width="100">频道名称</th>
        <th width="150">导航栏是否显示</th>
        <th>排序</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($channels as $channel){?>
      <tr class="text-c">
        <td><input name="" type="checkbox" value="<?=$channel['id']?>"></td>
        <td><?=$channel['id']?></td>
        <td><?=$channel['title']?></td>
         <td><?php if($channel['is_show']==1){echo '是';}else{ echo '否';}?></td>
        <td><?=$channel['sort']?></td>
        <td class="f-14 picture-manage"> <a style="text-decoration:none" class="ml-5" onClick="layer_show(750,'','频道编辑','<?=$this->url->get('admin/channel/edit/'.$channel['id'])?>')" href="javascript:;" title="编辑"><i class="icon-edit"></i></a> <a style="text-decoration:none" class="ml-5" onClick="one_del(this,'<?=$this->url->get('admin/channel/del/'.$channel['id'])?>')" href="javascript:;" title="删除"><i class="icon-trash"></i></a></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <div id="pageNav" class="pageNav"></div>
</div>