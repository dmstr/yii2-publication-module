<?php

use yii\db\Migration;

/**
 * Class m181025_081417_update_title_column
 */
class m181025_081417_update_title_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%dmstr_publication_item_translation}}','title','varchar(255) NOT NULL');
        $this->alterColumn('{{%dmstr_publication_category_translation}}','title','varchar(255) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%dmstr_publication_item_translation}}','title','varchar(80) NOT NULL');
        $this->alterColumn('{{%dmstr_publication_category_translation}}','title','varchar(80) NULL');
    }

}
