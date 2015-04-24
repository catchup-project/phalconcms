<?php

namespace Multiple\Backend\Controllers;
use Multiple\Components\Base;
use Phalcon\Db\Column as Column;
use Phalcon\Db\Index as Index;
use Phalcon\Db\Reference as Reference;

class ColumnsController extends Base
{

    /**
     * 添加字段
     */
	public function addAction()
	{
        //$exists = $this->db->tableExists("robots");//查看这个表是否存在；
        //$tables = $this->db->listTables("bingli");//查看这个库中有哪些表；

        $fields = $this->db->describeIndexes("robots");

        var_dump($fields);die();

        foreach ($fields as $index) {
            var_dump($index->getReferencedColumns());
        }

        die();


        var_dump($this->db->getColumnList("robots"));

        echo 999;

        die();
        echo $this->db->getColumnDefinition("robots");die();

        $this->db->addColumn("robots", null,
            new Column("robot_type", array(
                    "type"    => Column::TYPE_VARCHAR,
                    "size"    => 32,
                    'unsigned' => false,
                    "notNull" => true,
                    "after"   => "name"
                )
            )
        );

        $this->db->modifyColumn("robots", null, new Column("name", array(
            "type" => Column::TYPE_VARCHAR,
            "size" => 80,
            "notNull" => true,
        )));
	}

    /**
     * 删除字段；
     */

    public function dropAction(){
        $this->db->dropColumn('robots', null, 'name');//删除字段
    }

}