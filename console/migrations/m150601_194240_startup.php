<?php

use yii\db\Schema;
use yii\db\Migration;

class m150601_194240_startup extends Migration
{
    public function up()
    {
		$sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'admap.sql');
		foreach(explode('\n', $sql) as $q){
			$q = trim($q);
			if ($q != ''){
				$this->execute($q);
			}
		}
    }

    public function down()
    {
        echo "m150601_194240_startup cannot be reverted.\n";

        return false;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
