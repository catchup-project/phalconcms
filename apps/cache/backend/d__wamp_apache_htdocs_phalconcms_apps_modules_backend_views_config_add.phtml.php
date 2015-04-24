<div class="pd-20">
    <form class="Huiform" action="" method="post" >
      <table class="table table-border table-bordered table-bg">
        <tbody>
          <tr>
            <th class="text-r">标题：</th>
            <td><input type="text" style="width:300px" class="input-text" value="" placeholder="请输入中文"name="name"></td>
          </tr>
           <tr>
            <th class="text-r">变量名：</th>
            <td><input type="text" style="width:300px" class="input-text" value="" placeholder="请输入英文并大写"  name="title"></td>
          </tr>
          
           <tr>
            <th class="text-r">配置值 ：</th>
            <td><input type="text" style="width:300px" class="input-text" value="" placeholder=""  name="value"></td>
          </tr>
          
           <tr>
            <th class="text-r">配置组 ：</th>
            <td>
            <select name="group_id">
            	<?php foreach($groups as $group){?>
            		<option value="<?=$group['id']?>"><?=$group['title']?></option>
            	<?php }?>
            </select>
            </td>
          </tr>
          <tr>
            <th></th>
            <td><button class="btn btn-success radius" type="submit"><i class="icon-ok"></i> 确定</button></td>
          </tr>
        </tbody>
      </table>
    </form>
</div>