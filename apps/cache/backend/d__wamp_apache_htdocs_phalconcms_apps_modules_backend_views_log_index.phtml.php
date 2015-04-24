<nav class="Hui-breadcrumb"><i class="icon-home"></i> 首页 <span class="c-gray en">&gt;</span> 日志管理 <span class="c-gray en">&gt;</span> 日志 列表<a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="data_del('<?=$this->url->get('admin/log/del')?>')" class="btn btn-danger radius"><i class="icon-trash"></i> 批量删除</a> </span> <span class="r">共有数据：<strong><?=count($logs)?></strong> 条</span> </div>
  <table class="table table-border table-bordered table-bg table-hover table-sort">
    <thead>
      <tr class="text-c">
        <th width="40"><input name="" type="checkbox" value=""></th>
        <th width="80">ID</th>
        <th width="100">日志类型</th>
        <th width="150">日志内容</th>
        <th>时间</th>
        <th>操作者</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($logs as $log){?>
      <tr class="text-c">
        <td><input name="" type="checkbox" value="<?=$log['id']?>"></td>
        <td><?=$log['id']?></td>
        <td><?php if($log['type']==1){ echo '登录';}elseif($log['type']==2){echo '数据操作';}?></td>
        <td><?=$log['info']?></td>
        <td><?=date('Y-m-d H:i',$log['addtime'])?></td>
        <td class="f-14 picture-manage"> <a style="text-decoration:none" class="ml-5" onClick="one_del(this,'<?=$this->url->get('admin/log/del/'.$log['id'])?>')" href="javascript:;" title="删除"><i class="icon-trash"></i></a></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <div id="pageNav" class="pageNav"></div>
</div>