<nav class="Hui-breadcrumb"><i class="icon-home"></i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 节点管理 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <div class="cl pd-5 bg-1 bk-gray">
    <span class="l">
    		<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="icon-trash"></i> 批量删除</a>
    		<a class="btn btn-primary radius" href="javascript:;" onclick="layer_show('750','','添加节点','<?=$this->url->get('admin/node/add');?>')"><i class="icon-plus"></i> 添加节点</a>
    </span>
    <span class="r">共有数据：<strong><?=$count?></strong> 条</span>
  </div>
 <div class="pd-20">
  <form class="Huiform" id="loginform" action="" method="post">
    <table class="table table-border table-bordered table-bg">
      <tbody>
        <tr>
          <td>
            <table class="table table-border table-bordered table-bg">
              <tbody>
              <?php foreach($node as $item){?>
                <tr>
                  <th width="85">  <label class="item"><input name="" type="checkbox"  value="<?=$item['id']?>"> <?=$item['title']?></label></th>
                  <td class="permission-list">
               
                    <div class="cl">
                    <?php foreach($item['child'] as $child){?>
                    <label class="item"><input name="" type="checkbox"    value="<?=$child['id']?>"> <?=$child['title']?></label>
                    <?php }?>
                    </div>
                  </td>
                </tr>
                <?php }?>
              </tbody>
            </table>
          </td>
        </tr>
        
        <tr>
          <td>
            <button type="button" class="btn btn-success radius" id="update" > 修改</button>
             <button type="button" class="btn btn-success radius" id="delete"> 删除</button>
          
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
</div>

<script type="text/javascript">

</script>