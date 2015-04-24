<div class="pd-20">
    <form action="" method="post" class="Huiform">
      <table class="table table-border table-bordered table-bg">
        <tbody>
          <tr>
            <th width="100" class="text-r"><span class="c-red">*</span> 所属频道：</th>
            <td><select name="channel_id"><?php foreach($channels as $channel){?><option value="<?=$channel['id']?>"><?=$channel['title']?></option><?php }?></select></td>
          </tr>
          <tr>
            <th class="text-r"><span class="c-red">*</span> 所属栏目：</th>
            <td><select name="parent_id"><option value="0">顶级栏目 <option></select></td>
          </tr>
           <tr>
            <th class="text-r"><span class="c-red">*</span>栏目名称：</th>
            <td><input type="text"  value="" name="title" ></td>
          </tr>
           <tr>
            <th class="text-r"><span class="c-red">*</span>分类图片：</th>
            <td><input type="file" class="input-text" value="" name="image"><input type="hidden" name="image" id="image"></td>
            <td><div class="progress"><span class="bgpro"></span></div></td>
          </tr>
           <tr>
            <th class="text-r"><span class="c-red">*</span>栏目列表规则：</th>
            <td><input type="text" class="input-text" value="" name="list_rule"></td>
          </tr>
           <tr>
            <th class="text-r"><span class="c-red">*</span>内容页面规则：</th>
            <td><input type="text" class="input-text" value="" name="content_rule"></td>
          </tr>
           <tr>
            <th class="text-r"><span class="c-red">*</span>seo标题：</th>
            <td><input type="text" class="input-text" value="" name="seo_title"></td>
          </tr>
           <tr>
            <th class="text-r"><span class="c-red">*</span>seo关键字：</th>
            <td><input type="text" class="input-text" value="" name="seo_keyword"></td>
          </tr>
          <tr>
            <th class="text-r"><span class="c-red">*</span>seo描述：</th>
            <td><input type="text" class="input-text" value="" name="seo_description"></td>
          </tr>
          <tr>
            <th class="text-r"><span class="c-red">*</span>排序：</th>
            <td><input type="text" class="input-text" value="10" name="sort"></td>
          </tr>
          <tr>
            <th></th>
            <td><button class="btn btn-success radius" type="submit"><i class="icon-ok"></i> 确定</button></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>