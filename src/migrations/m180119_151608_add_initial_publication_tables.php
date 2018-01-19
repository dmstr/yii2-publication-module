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
            'content_widget_template_id' => $this->integer(),
            'teaser_widget_template_id' => $this->integer(),
            'name' => $this->string(80)->notNull()->unique(),
            'status' => "ENUM('draft', 'published') NOT NULL DEFAULT 'draft'",
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey('FK_dmstr_publication_category_hrzg_widget_template0','{{%dmstr_publication_category}}','content_widget_template_id','{{%hrzg_widget_template}}','id');
        $this->addForeignKey('FK_dmstr_publication_category_hrzg_widget_template1','{{%dmstr_publication_category}}','teaser_widget_template_id','{{%hrzg_widget_template}}','id');

        $this->createTable('{{%dmstr_publication_item}}', [
            'id' => $this->primaryKey(),
            'publication_category_id' => $this->integer(),
            'status' => "ENUM('draft', 'published') NOT NULL DEFAULT 'draft'",
            'release_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        $this->addForeignKey('FK_dmstr_publication_item_dmstr_publication_category0','{{%dmstr_publication_item}}','publication_category_id','{{%dmstr_publication_category}}','id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_dmstr_publication_item_dmstr_publication_category0','dmstr_publication_item');
        $this->dropTable('dmstr_publication_item');

        $this->dropForeignKey('FK_dmstr_publication_category_hrzg_widget_template1','dmstr_publication_category');
        $this->dropForeignKey('FK_dmstr_publication_category_hrzg_widget_template0','dmstr_publication_category');
        $this->dropTable('dmstr_publication_category');
    }
}
