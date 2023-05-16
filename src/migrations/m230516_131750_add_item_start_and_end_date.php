<?php

use yii\db\Migration;

/**
 * Class m230516_131750_add_item_start_and_end_date
 */
class m230516_131750_add_item_start_and_end_date extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%dmstr_publication_item_meta}}', 'item_start_date', $this->dateTime()->null()->after('end_date'));
        $this->addColumn('{{%dmstr_publication_item_meta}}', 'item_end_date', $this->dateTime()->null()->after('item_start_date'));
    }


    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%dmstr_publication_item_meta}}', 'item_start_date');
        $this->dropColumn('{{%dmstr_publication_item_meta}}', 'item_end_date');
    }
}
