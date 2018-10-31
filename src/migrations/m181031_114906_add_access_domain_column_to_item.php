<?php

use yii\db\Migration;

/**
 * Class m181031_114906_add_access_domain_column_to_item
 */
class m181031_114906_add_access_domain_column_to_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dmstr_publication_item}}','access_domain','CHAR(8) NOT NULL');
        // set initial to main lang
        $this->update('{{%dmstr_publication_item}}',['access_domain' => 'en']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%dmstr_publication_item}}','access_domain');

    }
}
