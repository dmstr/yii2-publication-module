<?php

namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationItem as PublicationItemModel;
use dmstr\modules\publication\models\crud\PublicationItemMeta;
use dmstr\modules\publication\models\crud\PublicationItemTranslation;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PublicationItem represents the model behind the search form about `dmstr\modules\publication\models\crud\PublicationItem`.
 *
 * @property string title
 * @property string release_date
 */
class PublicationItem extends PublicationItemModel
{

    /**
     *
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['release_date', 'title', 'category_id', 'ref_lang'], 'safe'],
        ];
    }


    /**
     *
     * @inheritdoc
     * @return array
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    /**
     * Creates data provider instance with search query applied
     *
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        // get the raw table names as we need to build constraints as string, see leftJoins below
        $model_table = Yii::$app->db->schema->getRawTableName(PublicationItemModel::tableName());
        $translation_table = Yii::$app->db->schema->getRawTableName(PublicationItemTranslation::tableName());
        $meta_table = Yii::$app->db->schema->getRawTableName(PublicationItemMeta::tableName());

        $query = PublicationItemModel::find();

        $query->select(["{$model_table}.*", 'meta.release_date as release_date', 'trans.title as title']);

        if (!empty(\Yii::$app->params['fallbackLanguages'][\Yii::$app->language])) {
            $query->select(["{$model_table}.*", 'COALESCE(meta.release_date, fbmeta.release_date) as release_date', 'COALESCE(trans.title, fbtrans.title) as title']);
            $query->leftJoin(
                "$translation_table as fbtrans",
                [
                    'AND',
                    "{$model_table}.id = fbtrans.item_id",
                     ['fbtrans.language' => \Yii::$app->params['fallbackLanguages'][\Yii::$app->language]]
                ]
            );

            $query->leftJoin(
                "$meta_table as fbmeta",
                [
                    'AND',
                    "{$model_table}.id = fbmeta.item_id",
                    ['fbmeta.language' => \Yii::$app->params['fallbackLanguages'][\Yii::$app->language]]
                ]
            );

        }

        // the column constraint must be definied as string, otherwise the right side will be quoted as string
        // see: https://www.yiiframework.com/doc/guide/2.0/en/db-query-builder#join
        $query->leftJoin(
            "$translation_table as trans",
            [
                'AND',
                "{$model_table}.id = trans.item_id",
                ['trans.language' => \Yii::$app->language]
            ]
        );

        $query->leftJoin(
            "$meta_table as meta",
            [
                'AND',
                "{$model_table}.id = meta.item_id",
                ['meta.language' => \Yii::$app->language]
            ]
        );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'title' => [
                        'asc' => ['title' => SORT_ASC],
                        'desc' => ['title' => SORT_DESC]
                    ],
                    'release_date' => [
                        'asc' => ['release_date' => SORT_ASC],
                        'desc' => ['release_date' => SORT_DESC]
                    ],
                    'category_id' => [
                        'asc' => [$model_table . '.category_id' => SORT_ASC],
                        'desc' => [$model_table . '.category_id' => SORT_DESC]
                    ],
                    'id' => [
                        'asc' => [$model_table . '.id' => SORT_ASC],
                        'desc' => [$model_table . '.id' => SORT_DESC]
                    ],
                    'ref_lang' => [
                        'asc' => [$model_table . '.ref_lang' => SORT_ASC],
                        'desc' => [$model_table . '.ref_lang' => SORT_DESC]
                    ]
                ],
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere([$model_table . '.id' => $this->id]);
        $query->andFilterWhere([$model_table . '.category_id' => $this->category_id]);
        if (!empty($this->title)) {
            $query->andFilterHaving(['LIKE', 'title', $this->title]);
        }

        if (!empty($this->release_date)) {
            $query->andFilterHaving(['release_date' => date('Y-m-d H:i:s', strtotime($this->release_date))]);
        }


        if (isset(\Yii::$app->params['fallbackLanguages'])) {

            $show_all = false;
            if (isset(\Yii::$app->params['headquarterRoleName'])) {
                if (Yii::$app->user->can(\Yii::$app->params['headquarterRoleName'])) {
                    $show_all = true;
                }
            }

            if (!$show_all) {
                $query->andWhere([
                    'OR',
                    [$model_table . '.ref_lang' => mb_strtolower(Yii::$app->language)],
                    [$model_table . '.ref_lang' => \Yii::$app->params['fallbackLanguages'][\Yii::$app->language] ?? false],
                ]);
            }

        }

        if (!empty($this->ref_lang)) {
            $query->andWhere(['LIKE', $model_table . '.ref_lang', $this->ref_lang]);
        }

        return $dataProvider;
    }


}
