<div class="pd-20">
  <form class="Huiform" id="loginform" action="" method="post">
    <table class="table table-border table-bordered table-bg">
      <tbody>
        <tr>
          <th class="text-r" width="80">节点名称：</th>
          <td><input name="title" type="text" class="input-text" id="" value="<?=$node['title']?>" > 
          </td>
        </tr>
        <tr>
          <th class="text-r" width="80">上级节点：</th>
          <td>
          	<select name="parent_id">
          		<option value="0">根节点</option>
          		<?php foreach($parent as $item){?>
          			<option value="<?=$item['id']?>"><?=$item['title']?></option>
          		<?php }?>
          	</select>
          </td>
        </tr>
        <tr>
          <th class="text-r" width="80">url：</th>
          <td><input name="url" type="text" class="input-text" id="" value="<?=$node['url']?>" > 
          </td>
        </tr>
         <tr>
          <th class="text-r" width="80">排序：</th>
          <td><input name="sort" type="text" class="input-text" id="" value="<?=$node['sort']?>"> 
          </td>
        </tr>
        <tr>
          <th class="text-r" width="80">是否显示在主菜单：</th>
          <td>
          是<input name="is_main" type="radio" id="" value="1" <?php if($node['is_main']==1){ echo 'checked="checked"';}?>> 
          否<input name="is_main" type="radio" id="" value="0" <?php if($node['is_main']==0){ echo 'checked="checked"';}?>> 
          </td>
        </tr>
        <tr>
          <th></th>
          <td>
            <button type="submit" class="btn btn-success radius" id="admin-role-save" name="admin-role-save"><i class="icon-ok"></i> 确定</button>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script> 
<script type="text/javascript" src="js/Validform_v5.3.2_min.js"></script> 
<script type="text/javascript" src="layer/layer.min.js"></script> 
<script type="text/javascript" src="js/H-ui.js"></script> 
<script type="text/javascript" src="js/H-ui.admin.js"></script> 
<script type="text/javascript">
$(".Huiform").Validform(); 
</script>