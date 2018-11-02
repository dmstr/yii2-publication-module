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
                'updated_at' => $this->integer(),
                'UNIQUE KEY `UQ_item_language_name` (`tag_id`,`language`,`name`)',
                'CONSTRAINT `FK_tag_translation0` FOREIGN KEY (`tag_id`) REFERENCES {{%dmstr_publication_tag}} (`id`)'
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%dmstr_publication_tag_x_item}}',
            [
                'item_id' => $this->integer()->notNull(),
                'tag_id' => $this->integer()->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'PRIMARY KEY (`item_id`,`tag_id`)',
                'UNIQUE KEY `dmstr_tag_x_item_unique` (`item_id`,`tag_id`)',
                'CONSTRAINT `fk_dmstr_tag_x_item0` FOREIGN KEY (`item_id`) REFERENCES {{%dmstr_publication_item}} (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
                'CONSTRAINT `fk_dmstr_tag_x_item1` FOREIGN KEY (`tag_id`) REFERENCES {{%dmstr_publication_tag}} (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
            ]);
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
