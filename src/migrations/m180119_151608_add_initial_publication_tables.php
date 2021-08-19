<?php

use yii\db\Migration;

/**
 * Class m180119_151608_add_initial_publication_tables
 */
class m180119_151608_add_initial_publication_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = $this->db->getDriverName() === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci' : null;

        $this->createTable('{{%dmstr_publication_category}}', [
            'id' => $this->primaryKey(),
            'content_widget_template_id' => $this->integer()->notNull(),
            'teaser_widget_template_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);
        $this->addForeignKey('FK_category_hrzg_widget_template0', '{{%dmstr_publication_category}}', 'content_widget_template_id', '{{%hrzg_widget_template}}', 'id');
        $this->addForeignKey('FK_category_hrzg_widget_template1', '{{%dmstr_publication_category}}', 'teaser_widget_template_id', '{{%hrzg_widget_template}}', 'id');

        $this->createTable('{{%dmstr_publication_category_translation}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'language_code' => $this->string(8)->notNull(),
            'title' => $this->string(80)->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);
        $this->addForeignKey('FK_category_translation_category_translation0', '{{%dmstr_publication_category_translation}}', 'category_id', '{{%dmstr_publication_category}}', 'id', 'CASCADE');

        $this->createTable('{{%dmstr_publication_item}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'release_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);
        $this->addForeignKey('FK_item_translation_category_translation0', '{{%dmstr_publication_item}}', 'category_id', '{{%dmstr_publication_category}}', 'id');


        if ($this->db->getDriverName() === 'pgsql') {
            $this->execute(<<<SQL
CREATE TYPE pit_status AS ENUM ('draft','published');
SQL
);
            $enumColumnConfig = "pit_status default 'draft'";
        } else {
            $enumColumnConfig = "ENUM('draft', 'published') NOT NULL DEFAULT 'draft'";
        }

        $this->createTable('{{%dmstr_publication_item_translation}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'language_code' => $this->string(8)->notNull(),
            'title' => $this->string(80),
            'content_widget_json' => $this->text(),
            'teaser_widget_json' => $this->text(),
            'status' => $enumColumnConfig,
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);
        $this->addForeignKey('FK_item_translation_item0', '{{%dmstr_publication_item_translation}}', 'item_id', '{{%dmstr_publication_item}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_item_translation_item0', '{{%dmstr_publication_item_translation}}');
        $this->dropTable('{{%dmstr_publication_item_translation}}');

        $this->dropForeignKey('FK_item_translation_category_translation0', '{{%dmstr_publication_item}}');
        $this->dropTable('{{%dmstr_publication_item}}');

        $this->dropForeignKey('FK_category_translation_category_translation0', '{{%dmstr_publication_category_translation}}');
        $this->dropTable('{{%{{%dmstr_publication_category_translation}}}}');

        $this->dropForeignKey('FK_category_hrzg_widget_template1', '{{%dmstr_publication_category}}');
        $this->dropForeignKey('FK_category_hrzg_widget_template0', '{{%dmstr_publication_category}}');
        $this->dropTable('{{%dmstr_publication_category}}');
    }
}
