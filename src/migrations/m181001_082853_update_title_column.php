<?php

use yii\db\Migration;

/**
 * Class m181001_082853_update_title_column
 */
class m181001_082853_update_title_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%dmstr_publication_item_translation}}','title','varchar(80) NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%dmstr_publication_item_translation}}','title','varchar(80) NULL DEFAULT NULL');
    }

}
