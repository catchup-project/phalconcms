<div class="pd-20">
  <form  action="" method="post"  class="Huiform">
    <table class="table table-border table-bordered table-bg">
      <tbody>
        <tr>
          <th class="text-r" width="80">角色名称：</th>
          <td><input name="name" type="text" class="input-text" placeholder="只能为英文" datatype="s4-20" nullmsg="角色名称不能为空！"   errormsg="角色名为4~20个英文字符！" value="<?=$role['name']?>" ></td>
         <td><div class="Validform_checktip" style="color:red"></div></td>
        </tr>
        <tr>
          <th class="text-r va-t">描述：</th>
          <td><textarea name="description" class="textarea"  placeholder="描述下角色所具有的权限" datatype="*5-50" style="width:250px; height:50px; resize:none" nullmsg="角色描述不能为空！" errormsg="角色描述 为5~50个任意字符！"><?=$role['description']?></textarea> </td>
        	<td><div class="Validform_checktip" style="color:red"></div></td>
        </tr>
        
        <tr>
          <th></th>
          <td>
            <button type="submit" class="btn btn-success radius"><i class="icon-ok"></i> 确定</button>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
