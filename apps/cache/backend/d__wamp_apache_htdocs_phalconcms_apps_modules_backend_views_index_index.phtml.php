<header class="Hui-header cl"> <a class="Hui-logo l" title="H-ui.admin v2.0" href="/">H-ui.admin</a> <a class="Hui-logo-m l" href="/" title="H-ui.admin">H-ui</a> <span class="Hui-subtitle l">V2.0</span> <span class="Hui-userbox"><span class="c-white">超级管理员：<?=$this->session->get('auth')['username']?></span> <a class="btn btn-danger radius ml-10" href="<?=$this->url->get('admin/login/logout')?>" title="退出"><i class="icon-off"></i> 退出</a></span> <a aria-hidden="false" class="Hui-nav-toggle" id="nav-toggle" href="#"></a> </header>
<div class="cl Hui-main">
  <aside class="Hui-aside" style="">
    <input runat="server" id="divScrollValue" type="hidden" value="" />
    <div class="menu_dropdown bk_2">
      <dl id="menu_1">
        <dt><i class="icon-user"></i> 用户中心<b></b></dt>
        <dd>
          <ul>
            <li><a _href="<?=$this->url->get('admin/member/index')?>" >用户管理</a></li>
          </ul>
        </dd>
      </dl>
      <dl id="menu_2">
        <dt><i class="icon-edit"></i> 网站内容管理<b></b></dt>
        <dd>
          <ul>
            <li><a  _href="<?=$this->url->get('admin/channel/index')?>">频道管理</a></li>
            <li><a   _href="<?=$this->url->get('admin/category/index')?>">栏目管理</a></li>
            <li><a   _href="<?=$this->url->get('admin/article/index')?>">文章管理</a></li>
          </ul>
        </dd>
      </dl>

     
      <dl id="menu-page">
        <dt><i class="icon-paste"></i> 页面管理<b></b></dt>
        <dd>
          <ul>
            <li id="menu-page-flink"><a _href="page-flink.html" href="javascript:void(0)">友情链接</a></li>
          </ul>
        </dd>
      </dl>
      <dl >
        <dt><i class="icon-key"></i> 管理员管理<b></b></dt>
        <dd>
          <ul>
            <li ><a _href="<?=$this->url->get('admin/role/index')?>" >角色管理</a></li>
            <li ><a _href="<?=$this->url->get('admin/node/index')?>">节点管理</a></li>
            <li ><a _href="<?=$this->url->get('admin/user/index')?>" >管理员列表</a></li>
          </ul>
        </dd>
      </dl>
      <dl id="menu-system">
        <dt><i class="icon-cogs"></i> 系统管理<b></b></dt>
        <dd>
          <ul>
            <li><a _href="<?=$this->url->get('admin/log/index')?>" href="javascript:void(0)">日志管理</a></li>
            <li><a _href="<?=$this->url->get('admin/configGroup/index')?>" href="javascript:void(0)">配置组</a></li>
             <li><a _href="<?=$this->url->get('admin/config/index')?>" href="javascript:void(0)">网站配置</a></li>
            <li id="menu-system-base"><a _href="<?=$this->url->get('admin/backup/index')?>" href="javascript:void(0)">数据备份</a></li>
			 </ul>
        </dd>
      </dl>

    </div>
  </aside>
  <div class="dislpayArrow"><a class="pngfix" href="javascript:void(0);"></a></div>
  <section class="Hui-article">
    <div id="Hui-tabNav" class="Hui-tabNav">
      <div class="Hui-tabNav-wp">
        <ul id="min_title_list" class="acrossTab cl">
          <li class="active"><span title="我的桌面" data-href="welcome.html">我的桌面</span><em></em></li>
        </ul>
        
      </div>
      <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default btn-small" href="javascript:;"><i class="icon-step-backward"></i></a><a id="js-tabNav-next" class="btn radius btn-default btn-small" href="javascript:;"><i class="icon-step-forward"></i></a></div>
    </div>
    <div id="iframe_box" class="Hui-articlebox">
      <div class="show_iframe">
        <div style="display:none" class="loading"></div>
        <iframe scrolling="yes" frameborder="0" src="<?=$this->url->get('admin/index/welcome')?>"></iframe>
      </div>
    </div>
  </section>
</div>
<script type="text/javascript">
/*var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F080836300300be57b7f34f4b3e97d911' type='text/javascript'%3E%3C/script%3E"));*/
</script>