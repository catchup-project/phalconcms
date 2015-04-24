<div class="pd-20">
    <form class="Huiform" action="" method="post" enctype="multipart/form-data">
      <table class="table table-border table-bordered table-bg">
        <tbody>
          <tr>
            <th width="100" class="text-r"><span class="c-red">*</span> 用户名：</th>
            <td><input type="text" style="width:200px" class="input-text" value="" placeholder="请输入用户名"  name="username" datatype="*2-16" nullmsg="用户名不能为空"></td>
          </tr>
          <tr>
            <th class="text-r"><span class="c-red">*</span> 性别：</th>
            <td><label>
                <input name="sex" type="radio" id="six_1" value="1" checked>
                男</label>
              <label>
                <input type="radio" name="sex" value="0" id="six_0">
                女</label></td>
          </tr>
          <tr>
            <th class="text-r"><span class="c-red">*</span> 手机：</th>
            <td><input type="text" style="width:300px" class="input-text" value="" placeholder="" id="user-tel" name="phone"></td>
          </tr>
          <tr>
            <th class="text-r">邮箱：</th>
            <td><input type="text" style="width:300px" class="input-text" value="" placeholder="" id="user-email" name="email"></td>
          </tr>
          <tr>
            <th class="text-r">头像：</th>
            <td><input type="file" class="" name="photo" multiple></td>
          </tr>
          <tr>
            <th class="text-r">地址：</th>
            <td><input type="text" style="width:300px" class="input-text" value="" placeholder="" id="user-address" name="address"></td>
          </tr>
          <tr>
            <th class="text-r">简介：</th>
            <td><textarea class="input-text" name="description" id="user-info" style="height:100px;width:300px;"></textarea></td>
          </tr>
          <tr>
            <th></th>
            <td><button class="btn btn-success radius" type="submit"><i class="icon-ok"></i> 确定</button></td>
          </tr>
        </tbody>
      </table>
    </form>
</div>