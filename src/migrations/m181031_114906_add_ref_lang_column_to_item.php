<?php

use yii\db\Migration;

/**
 * Class m181031_114906_add_ref_lang_column_to_item
 */
class m181031_114906_add_ref_lang_column_to_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dmstr_publication_item}}', 'ref_lang', 'CHAR(8) NOT NULL');
        $this->addColumn('{{%dmstr_publication_category}}', 'ref_lang', 'CHAR(8) NOT NULL');
        // set initial to main lang
        $this->update('{{%dmstr_publication_item}}', ['ref_lang' => 'en']);
        $this->update('{{%dmstr_publication_category}}', ['ref_lang' => 'en']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%dmstr_publication_item}}', 'ref_lang');
        $this->dropColumn('{{%dmstr_publication_category}}', 'ref_lang');

    }
}
