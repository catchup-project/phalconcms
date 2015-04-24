<?php

namespace Multiple\Frontend\Controllers;
use Multiple\Components\Base;
use Multiple\Models\Users;
use Phalcon\Http\Response;

class ForestryController extends Base
{

    public function initialize()
    {
        $this->view->setTemplateAfter('forestry');
        $this->tag->setTitle('Manage your companies');
        parent::initialize();
    }

    public function indexAction(){

    }

    public function sys_authority_getAuthorityAction(){
        $str = '[{"id":1,"sortOrder":100,"menuCode":"EnvironmentMonitor","text":"林区环境监控","menuConfig":" ","buttons":" ","expanded":true,"leaf":false,"url":" ","iconCls":" ","children":[{"id":2,"sortOrder":1001,"menuCode":"TemperatureDistribution","text":"林区温度分布","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"environmentMonitor.TemperatureDistribution","iconCls":" "},{"id":3,"sortOrder":1002,"menuCode":"HumidityDistribution","text":"林区湿度分布","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"environmentMonitor.HumidityDistribution","iconCls":" "},{"id":4,"sortOrder":1003,"menuCode":"LightfallDistribution","text":"林区光照度分布","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"environmentMonitor.LightfallDistribution","iconCls":" "},{"id":5,"sortOrder":1004,"menuCode":"Sensor","text":"传感器位置标识","menuConfig":" ","buttons":"Add,Edit,Delete,View,","expanded":true,"leaf":true,"url":"environmentMonitor.Sensor","iconCls":" "}]},{"id":6,"sortOrder":200,"menuCode":"ForestryManage","text":"树木信息管理","menuConfig":" ","buttons":" ","expanded":true,"leaf":false,"url":" ","iconCls":" ","children":[{"id":7,"sortOrder":2001,"menuCode":"ForestryTypeList","text":"树木种类信息录入","menuConfig":" ","buttons":"Add,Edit,Delete,View,","expanded":true,"leaf":true,"url":"forestryManage.ForestryTypeList","iconCls":" "},{"id":8,"sortOrder":2002,"menuCode":"ForestryEntry","text":"树木识别信息录入","menuConfig":" ","buttons":"Add,Edit,Delete,View,","expanded":true,"leaf":true,"url":"forestryManage.ForestryEntry","iconCls":" "},{"id":9,"sortOrder":2003,"menuCode":"ForestryImport","text":"树木识别数据导入","menuConfig":" ","buttons":"Import,Edit,Delete,View,Export,","expanded":true,"leaf":true,"url":"forestryManage.ForestryImport","iconCls":" "},{"id":10,"sortOrder":2004,"menuCode":"ForestryQuery","text":"树木识别信息查询","menuConfig":" ","buttons":"View,Query,","expanded":true,"leaf":true,"url":"forestryManage.ForestryQuery","iconCls":" "}]},{"id":11,"sortOrder":300,"menuCode":"ForestryMonitor","text":"树木在园监控","menuConfig":" ","buttons":" ","expanded":true,"leaf":false,"url":" ","iconCls":" ","children":[{"id":12,"sortOrder":3001,"menuCode":"ForestryIdentification","text":"树木位置标识","menuConfig":" ","buttons":"Add,Edit,Delete,View,","expanded":true,"leaf":true,"url":"forestryMonitor.ForestryIdentification","iconCls":" "},{"id":13,"sortOrder":3002,"menuCode":"ForestryDistribution","text":"树木位置分布","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"forestryMonitor.ForestryDistribution","iconCls":" "},{"id":14,"sortOrder":3003,"menuCode":"ForestryAlarm","text":"树木失踪警报","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"forestryMonitor.ForestryAlarm","iconCls":" "}]},{"id":15,"sortOrder":400,"menuCode":"SystemManage","text":"系统管理","menuConfig":" ","buttons":" ","expanded":true,"leaf":false,"url":" ","iconCls":" ","children":[{"id":16,"sortOrder":4001,"menuCode":"UserManagement","text":"用户管理","menuConfig":" ","buttons":"Add,Edit,Delete,View,","expanded":true,"leaf":true,"url":"systemManage.UserManagement","iconCls":" "},{"id":17,"sortOrder":4002,"menuCode":"AuthorizationManagement","text":"权限管理","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"systemManage.AuthorizationManagement","iconCls":" "},{"id":18,"sortOrder":4003,"menuCode":"AlarmConfiguration","text":"警报设置","menuConfig":" ","buttons":"Add,Edit,Delete,View,","expanded":true,"leaf":true,"url":"systemManage.AlarmConfiguration","iconCls":" "},{"id":19,"sortOrder":4004,"menuCode":"ResourceManagement","text":"资源管理","menuConfig":" ","buttons":"Add,Edit,Delete,View,","expanded":true,"leaf":true,"url":"systemManage.ResourceManagement","iconCls":" "},{"id":20,"sortOrder":4005,"menuCode":"DepartmentManagement","text":"部门管理","menuConfig":" ","buttons":"Add,Edit,Delete,View,","expanded":true,"leaf":true,"url":"systemManage.DepartmentManagement","iconCls":" "}]},{"id":21,"sortOrder":500,"menuCode":"Report","text":"报表","menuConfig":" ","buttons":" ","expanded":true,"leaf":false,"url":" ","iconCls":" ","children":[{"id":22,"sortOrder":5001,"menuCode":"TemperatureReport","text":"林区温度变化表","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"report.TemperatureReport","iconCls":" "},{"id":23,"sortOrder":5002,"menuCode":"HumidityReport","text":"林区湿度变化表","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"report.HumidityReport","iconCls":" "},{"id":24,"sortOrder":5003,"menuCode":"LightfallReport","text":"林区光照度变化表","menuConfig":" ","buttons":"","expanded":true,"leaf":true,"url":"report.LightfallReport","iconCls":" "}]}]';

        $this->sendJson($str);
    }

}