<?php

use yii\db\Migration;

/**
 * Class m181031_131819_add_tag_tables
 */
class m181031_131819_add_tag_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = $this->db->getDriverName() === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci' : null;

        $this->createTable('{{%dmstr_publication_tag}}',
            [
                'id' => $this->primaryKey(),
                'ref_lang' => $this->char(7)->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer()
            ]);

        $this->createTable('{{%dmstr_publication_tag_translation}}',
            [
                'id' => $this->primaryKey(),
                'tag_id' => $this->integer()->notNull(),
                'language' => $this->char(7)->notNull(),
                'name' => $this->string(128)->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer()
            ],
            $tableOptions);
        $this->createIndex('UQ_item_language_name','{{%dmstr_publication_tag_translation}}', ['tag_id', 'language', 'name'], true);
        $this->addForeignKey('FK_tag_translation0','{{%dmstr_publication_tag_translation}}', 'tag_id', '{{%dmstr_publication_tag}}', 'id');


        $this->createTable('{{%dmstr_publication_tag_x_item}}',
            [
                'item_id' => $this->integer()->notNull(),
                'tag_id' => $this->integer()->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'PRIMARY KEY (item_id, tag_id)'
            ]);
        $this->addForeignKey('fk_dmstr_tag_x_item0','{{%dmstr_publication_tag_x_item}}', 'item_id', '{{%dmstr_publication_item}}', 'id','CASCADE', 'CASCADE');
        $this->addForeignKey('fk_dmstr_tag_x_item1','{{%dmstr_publication_tag_x_item}}', 'tag_id', '{{%dmstr_publication_tag}}', 'id','CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dmstr_publication_tag_x_item}}');
        $this->dropTable('{{%dmstr_publication_tag_translation}}');
        $this->dropTable('{{%dmstr_publication_tag}}');
    }
}
