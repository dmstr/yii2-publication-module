<?php

use yii\db\Migration;

/**
 * Class m180720_112603_add_index_to_tables
 */
class m180720_112603_add_index_to_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('UQ_category_language_category0','{{%dmstr_publication_category_translation}}',['category_id','language'],true);
        $this->createIndex('UQ_item_language_translation0','{{%dmstr_publication_item_translation}}',['item_id','language'],true);
        $this->createIndex('UQ_item_language_meta0','{{%dmstr_publication_item_meta}}',['item_id','language'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('UQ_category_language_category0','{{%dmstr_publication_category_translation}}');
        $this->dropIndex('UQ_item_language_translation0','{{%dmstr_publication_item_translation}}');
        $this->dropIndex('UQ_item_language_meta0','{{%dmstr_publication_item_meta}}');
    }
}
