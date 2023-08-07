<?php

use yii\db\Migration;

/**
 * Class m230807_084856_add_tag_group_tables
 */
class m230807_084856_add_tag_group_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {

        $tableOptions = $this->db->getDriverName() === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci' : null;

        $this->createTable('{{%dmstr_publication_tag_group}}',
            [
                'id' => $this->primaryKey(),
                'ref_lang' => $this->char(7)->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer()
            ], $tableOptions);

        $this->createTable('{{%dmstr_publication_tag_group_translation}}',
            [
                'id' => $this->primaryKey(),
                'tag_group_id' => $this->integer()->notNull(),
                'language' => $this->char(7)->notNull(),
                'name' => $this->string(128)->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer()
            ],
            $tableOptions);
        $this->createIndex('UQ_tag_group_language_name', '{{%dmstr_publication_tag_group_translation}}', ['tag_group_id', 'language', 'name'], true);
        $this->addForeignKey('FK_tag_group_translation0', '{{%dmstr_publication_tag_group_translation}}', 'tag_group_id', '{{%dmstr_publication_tag_group}}', 'id');


        $this->createTable('{{%dmstr_publication_tag_group_x_tag}}',
            [
                'tag_group_id' => $this->integer()->notNull(),
                'tag_id' => $this->integer()->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'PRIMARY KEY (tag_group_id, tag_id)'
            ], $tableOptions);
        $this->addForeignKey('fk_dmstr_tag_group_x_item0', '{{%dmstr_publication_tag_group_x_tag}}', 'tag_group_id', '{{%dmstr_publication_tag_group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_dmstr_tag_group_x_item1', '{{%dmstr_publication_tag_group_x_tag}}', 'tag_id', '{{%dmstr_publication_tag}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%dmstr_publication_tag_group_x_item}}');
        $this->dropTable('{{%dmstr_publication_tag_group_translation}}');
        $this->dropTable('{{%dmstr_publication_tag_group}}');
    }

}
