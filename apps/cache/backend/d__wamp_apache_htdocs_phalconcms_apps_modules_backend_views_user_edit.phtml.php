<div class="pd-20">
    <form class="Huiform" action="<?=$this->url->get('admin/user/edit/'.$user['id'])?>" method="post" enctype="multipart/form-data">
      <table class="table table-border table-bordered table-bg">
        <tbody>
          <tr>
            <th width="100" class="text-r"><span class="c-red">*</span> 用户名：</th>
            <td><input type="text" style="width:200px" class="input-text" value="<?=$user['username']?>" placeholder="" name="username" datatype="*2-16" nullmsg="用户名不能为空"></td>
          </tr>
          <tr>
            <th class="text-r">密码：</th>
            <td><input type="password" style="width:300px" class="input-text" value="" placeholder=""  name="password"></td>
          </tr>
          <tr>
            <th class="text-r"><span class="c-red">*</span> 手机：</th>
            <td><input type="text" style="width:300px" class="input-text" value="<?=$user['phone']?>" placeholder=""  name="phone"></td>
          </tr>
          <tr>
            <th class="text-r"><span class="c-red">*</span> 排序：</th>
            <td><input type="text" style="width:300px" class="input-text" value="<?=$user['sort']?>" placeholder=""name="sort"></td>
          </tr>
          <tr>
            <th class="text-r">邮箱：</th>
            <td><input type="text" style="width:300px" class="input-text" value="<?=$user['email']?>" placeholder="" name="email"></td>
          </tr>
          <tr>
            <th class="text-r">角色：</th>
            <td>
            <?php foreach($roles as $role){?>
           <?=$role['name']?> <input type="checkbox"  name="role_id[]" value="<?=$role['id']?>" <?php if(in_array($role['id'],$userRole)){ echo "checked='checked'";}?>/>
            <?php }?>
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