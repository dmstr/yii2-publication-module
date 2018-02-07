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
        $this->createTable('{{%dmstr_publication_category}}', [
            'id' => $this->primaryKey(),
            'content_widget_template_id' => $this->integer()->notNull(),
            'teaser_widget_template_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey('FK_category_hrzg_widget_template0','{{%dmstr_publication_category}}','content_widget_template_id','{{%hrzg_widget_template}}','id');
        $this->addForeignKey('FK_category_hrzg_widget_template1','{{%dmstr_publication_category}}','teaser_widget_template_id','{{%hrzg_widget_template}}','id');

        $this->createTable('{{%dmstr_publication_category_translation}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'language_code' => $this->string(8)->notNull(),
            'title' => $this->string(80)->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey('FK_category_translation_category_translation0','{{%dmstr_publication_category_translation}}','category_id','{{%dmstr_publication_category}}','id');

        $this->createTable('{{%dmstr_publication_item}}', [
            'id' => $this->primaryKey(),
            'release_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%dmstr_publication_item_translation}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'publication_category_id' => $this->integer()->notNull(),
            'language_code' => $this->string(8)->notNull(),
            'title' => $this->string(80),
            'content_widget_json' => $this->text(),
            'teaser_widget_json' => $this->text(),
            'status' => "ENUM('draft', 'published') NOT NULL DEFAULT 'draft'",
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey('FK_item_translation_item0','{{%dmstr_publication_item_translation}}','item_id','{{%dmstr_publication_item}}','id');
        $this->addForeignKey('FK_item_translation_category_translation0','{{%dmstr_publication_item_translation}}','publication_category_id','{{%dmstr_publication_category_translation}}','id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_item_translation_item0','{{%dmstr_publication_item_translation}}');
        $this->dropForeignKey('FK_item_translation_category_translation0','{{%dmstr_publication_item_translation}}');
        $this->dropTable('{{%dmstr_publication_item_translation}}');

        $this->dropTable('{{%dmstr_publication_item}}');

        $this->dropForeignKey('FK_category_translation_category_translation0','{{%dmstr_publication_category_translation}}');
        $this->dropTable('{{%{{%dmstr_publication_category_translation}}}}');

        $this->dropForeignKey('FK_category_hrzg_widget_template1','{{%dmstr_publication_category}}');
        $this->dropForeignKey('FK_category_hrzg_widget_template0','{{%dmstr_publication_category}}');
        $this->dropTable('{{%dmstr_publication_category}}');
    }
}
