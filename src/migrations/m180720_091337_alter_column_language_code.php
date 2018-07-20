<?php

use yii\db\Migration;

/**
 * Class m180720_091337_alter_column_language_code
 */
class m180720_091337_alter_column_language_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%dmstr_publication_category_translation}}','language_code', 'CHAR(7) NOT NULL');
        $this->renameColumn('{{%dmstr_publication_category_translation}}','language_code','language');
        $this->alterColumn('{{%dmstr_publication_item_translation}}','language_code', 'CHAR(7) NOT NULL');
        $this->renameColumn('{{%dmstr_publication_item_translation}}','language_code','language');
        $this->alterColumn('{{%dmstr_publication_item_meta}}','language_code', 'CHAR(7) NOT NULL');
        $this->renameColumn('{{%dmstr_publication_item_meta}}','language_code','language');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%dmstr_publication_category_translation}}','language', 'VARCHAR(8) NOT NULL');
        $this->renameColumn('{{%dmstr_publication_category_translation}}','language','language_code');
        $this->alterColumn('{{%dmstr_publication_item_translation}}','language', 'VARCHAR(8) NOT NULL');
        $this->renameColumn('{{%dmstr_publication_item_translation}}','language','language_code');
        $this->alterColumn('{{%dmstr_publication_item_meta}}','language', 'VARCHAR(8) NOT NULL');
        $this->renameColumn('{{%dmstr_publication_item_meta}}','language','language_code');
    }

}
