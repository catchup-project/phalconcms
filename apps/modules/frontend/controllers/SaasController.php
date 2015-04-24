<?php

namespace Multiple\Frontend\Controllers;
use Multiple\Components\Base;
use Multiple\Library\Pagination as Pagination;  //调用自己写的分页类；

use Multiple\Models\Users;

use Phalcon\Http\Response;

use Phalcon\Filter;

class SaasController extends Base
{

    public function initialize()
    {
        $this->view->setTemplateAfter('saas');
        $this->tag->setTitle('Manage your companies');
        parent::initialize();
    }

    /**
     * 默认首页
     */
	public function indexAction()
	{

	}

    /**
     * 获取导航菜单
     */
    public function getmenuAction(){
        $str = "[{text:'认证授权中心',expanded:true,id:'c71e4cdd-d187-4b4e-a0f9-1c63fea143e5',children: [{text:'组织架构',url:'/saas/org',key:'zzjg',leaf:true,iconCls:'User',id:'f67997ff-d44e-4cf5-9215-bba48565089b'},{text:'系统注册',url:'/saas/system',key:'xtzc',leaf:true,iconCls:'DatabaseConnect',id:'91ad58f0-dda0-49c4-994a-c8d65a8f2e51'},{text:'角色权限',url:'/saas/role',key:'jsqx',leaf:true,iconCls:'ShieldRainbow',id:'16695ef4-416c-4c8f-8e6d-d95a80a1c2a6'}]},{text:'系统监控中心',expanded:true,id:'0b9f3fd4-5d48-496c-afa3-aec53ab134c4',children: [{text:'系统监控',url:'/saas/monitor',key:'fwl',leaf:true,iconCls:'ChartBar',id:'155eef86-79f1-488b-aa57-03caa0d7aba2'},{text:'在线情况',url:'/saas/log/',key:'zxqk',leaf:true,iconCls:'UserComment',id:'49e76d4f-d6e2-49d2-9009-5cff9b0ccfea'},{text:'异常日志',url:'/saas/lognet',key:'ycrz',leaf:true,iconCls:'Exclamation',id:'7880ab6f-8fa6-4b5b-bf25-c896dca35385'}]}]";
        $this->sendJson($str);
    }

    /**
     * 组织架构
     */
    public function orgAction(){

    }


    /**
     *
     */
    public function getorgAction(){
        $str = "[{text:'华辰时代科技（北京）有限公司',leaf:true,iconCls:'Group',id:'7a944b4a-aa28-4078-845f-b977df72d6f1'}]";
        $this->sendJson($str);
    }



    public function getcurnameAction()
    {
        echo "管理员";
    }


    /**
     * 系统注册
     */
    public function systemAction(){

    }

    public function getsystemAction(){
        $str = '{"data":[{"Id":"4b3940e1-16e9-40fe-968b-8f2b11429b44","SysName":"demo","SysType":"B/S","SysUrl":"http://saas.chinacloudtech.com/Admin/Main","SysLanguage":"win7","SysAdmin":"0","Disable":true,"SysRemark":"00","CreateUser":"admin","CreateTime":"\/Date(1418872736653)\/","ModifyUser":null,"ModifyTime":null}],"total":1}';
        $this->sendJson($str);
    }

    public function putsystemAction(){
        $str =  '{"result":0,"msg":"系统信息保存成功！","success":true}';
        $this->sendJson($str);
    }

    public function deletesystemAction(){
        $str = '{"result":0,"msg":"系统信息删除成功！","success":true}';
        $this->sendJson($str);
    }

    public function roleAction(){

    }

    public function getallsystemAction(){
        $str  = '[{"Id":"7c24d0e1-42f5-44a7-bf5f-2994265c9ab2","SysName":"WebMisCentral"},{"Id":"4b3940e1-16e9-40fe-968b-8f2b11429b44","SysName":"demo"},{"Id":"78c8542f-be2c-42a4-b9f0-15b6b901eb0a","SysName":"111118"},{"Id":"c31df800-dc5e-4e7a-a8bb-b3920f39ab58","SysName":"888888"}]';
        $this->sendJson($str);
    }


    public function monitorAction(){

    }

    public function getsysonlineAction(){
        $str  = '[{"Id":"78c8542f-be2c-42a4-b9f0-15b6b901eb0a","SysName":"111118","TokenId":0,"Visiter":0},{"Id":"c31df800-dc5e-4e7a-a8bb-b3920f39ab58","SysName":"888888","TokenId":0,"Visiter":0},{"Id":"4b3940e1-16e9-40fe-968b-8f2b11429b44","SysName":"demo","TokenId":0,"Visiter":0}]';
        $this->sendJson($str);
    }

    public function orggetuserAction(){
        $str = '{"data":[{"Id":"c1807bc2-8ba5-45d6-aa3f-265fd3d065fb","UserName":"admin","Address":"华辰时代科技（北京）有限公司","Email":"yangcuiwang@gmail.com","Sex":"男","CreateUser":"WMC","CreateTime":"\/Date(1416982658413)\/","NickName":"管理员","Phone":"13911052021","QQ":null,"Remark":"华辰时代科技（北京）有限公司","Disable":false,"UserPwd":"55H=FJJC959JF55C4I7JFJE4HJGCGC8J"}],"total":1}';
        $this->sendJson($str);
    }

    public function orggetuserorgsAction(){
        $str = '[{"Id":"9f93680b-3fe8-410a-b718-d6ad6f224022","OrgId":"7a944b4a-aa28-4078-845f-b977df72d6f1"}]';
        $this->sendJson($str);
    }

    public function userrolegetrolebyuserAction(){
        $str = '[{"Id":"a6ebcb2f-3513-4a2f-8eee-39127696241d","RoleId":"7c24d0e1-42f5-44a7-bf5f-2994265c9ab2","UserId":"c1807bc2-8ba5-45d6-aa3f-265fd3d065fb","RoleName":"SupperRole","SysName":"WebMisCentral"}]';
        $this->sendJson($str);
    }

    public function userupdateAction(){
        $str = '{"result":0,"msg":"用户信息更新成功！","success":true}';
        $this->sendJson($str);

    }
    /**
     * 在线情况；
     */
    public function logAction(){}


    public function lognetAction(){

    }

}