<?php

use yii\db\Schema;
use yii\db\Migration;

class m140817_091022_InitRegion extends Migration
{
    public function up()
    {
        $this->createTable('{{%regions}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            'parent_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'type' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'abbr' => Schema::TYPE_STRING. '(32) NOT NULL DEFAULT ""',
            'gb_code' => Schema::TYPE_STRING . '(32) NOT NULL DEFAULT ""',
            'pinyin' => Schema::TYPE_STRING . '(32) NOT NULL DEFAULT ""',

            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');

        $sql = file_get_contents(__DIR__ . "/regions.sql");

        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable('{{%regions}}');
    }
}
