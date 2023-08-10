<?php

use yii\db\Migration;
use Yii;

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

        // ref lang. Use fall back language param as default. If not set use fallback language for specific language. If not set, use first language from url manager. If not set use app language
        $refLang = Yii::$app->params['fallbackLanguage'] ?? Yii::$app->params['fallbackLanguages'][Yii::$app->language] ?? Yii::$app->urlManager->languages[0] ?? Yii::$app->language;
        $defaultTagGroupId = 1;

        // create default tag group
        $this->insert('{{%dmstr_publication_tag_group}}', ['id' => $defaultTagGroupId, 'ref_lang' => $refLang, 'created_at' => time(), 'updated_at' => time()]);
        $this->insert('{{%dmstr_publication_tag_group_translation}}', ['tag_group_id' => $defaultTagGroupId, 'language' => $refLang, 'name' => 'Default', 'created_at' => time(), 'updated_at' => time()]);

        // create fk column for tag
        $this->addColumn('{{%dmstr_publication_tag}}', 'tag_group_id', $this->integer()->null()->after('id'));
        $this->addForeignKey('FK_tag_tag_group_0', '{{%dmstr_publication_tag}}', 'tag_group_id', '{{%dmstr_publication_tag_group}}', 'id');

        // update tag entries
        $this->update('{{%dmstr_publication_tag}}', ['tag_group_id' => $defaultTagGroupId]);

        // tag group ref in tag is required
        $this->alterColumn('{{%dmstr_publication_tag}}', 'tag_group_id', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%dmstr_publication_tag}}', 'tag_group_id');
        $this->dropTable('{{%dmstr_publication_tag_group_translation}}');
        $this->dropTable('{{%dmstr_publication_tag_group}}');
    }

}
