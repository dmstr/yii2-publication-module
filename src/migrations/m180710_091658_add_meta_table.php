<?php

use yii\db\Migration;

/**
 * Class m180710_091658_add_meta_table
 */
class m180710_091658_add_meta_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dmstr_publication_item_meta}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'language_code' => $this->string(8)->notNull(),
            'status' => "ENUM('draft', 'published') NOT NULL DEFAULT 'draft'",
            'release_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey(
            'fk_publication_translation_meta_id',
            '{{%dmstr_publication_item_meta}}',
            'item_id',
            '{{%dmstr_publication_item}}',
            'id',
            'CASCADE',
            'CASCADE');


        $query = new \yii\db\Query();
        $query->select([
            'item_id',
            'language_code',
            'status',
            'release_date',
            'end_date'
        ])->from('{{%dmstr_publication_item}}')->leftJoin('{{%dmstr_publication_item_translation}}', '{{%dmstr_publication_item}}.id=item_id');

        $publications = $query->all();

        foreach ($publications as $publication) {
            $this->insert('{{%dmstr_publication_item_meta}}', [
                'item_id' => $publication['item_id'],
                'language_code' => $publication['language_code'],
                'status' => $publication['status'],
                'release_date' => $publication['release_date'],
                'end_date' => $publication['end_date'],
                'created_at' => time(),
                'updated_at' => time()
            ]);
        }

        $this->dropColumn('{{%dmstr_publication_item}}','release_date');
        $this->dropColumn('{{%dmstr_publication_item}}','end_date');
        $this->dropColumn('{{%dmstr_publication_item_translation}}','status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%dmstr_publication_item}}','release_date','datetime NULL AFTER category_id');
        $this->addColumn('{{%dmstr_publication_item}}','end_date','datetime NULL AFTER release_date');
        $this->addColumn('{{%dmstr_publication_item_translation}}','status',"enum('draft','published') COLLATE utf8_unicode_ci NULL DEFAULT 'draft' AFTER teaser_widget_json");

        $query = new \yii\db\Query();
        $query->select([
            'item_id',
            'language_code',
            'status',
            'release_date',
            'end_date'
        ])->from('{{%dmstr_publication_item_meta}}');

        $publicationMetas = $query->all();

        foreach ($publicationMetas as $publicationMeta) {
            $this->update('{{%dmstr_publication_item}}', [
                'release_date' => $publicationMeta['release_date'],
                'end_date' => $publicationMeta['end_date']
            ],['id' => $publicationMeta['item_id']]);

            $this->update('{{%dmstr_publication_item_translation}}', [
                'status' => $publicationMeta['status'],
            ],['item_id' => $publicationMeta['item_id'], 'language_code' => $publicationMeta['language_code']]);
        }

        $this->alterColumn('{{%dmstr_publication_item}}','release_date','datetime NOT NULL AFTER category_id');
        $this->alterColumn('{{%dmstr_publication_item_translation}}','status',"enum('draft','published') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft' AFTER teaser_widget_json");

        $this->dropTable('{{%dmstr_publication_item_meta}}');

    }
}
