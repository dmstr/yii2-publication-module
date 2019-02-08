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
        $model_table = PublicationItemModel::tableName();
        $translation_table = PublicationItemTranslation::tableName();
        $meta_table = PublicationItemMeta::tableName();

        $query = PublicationItemModel::find();

        $query->joinWith('translations');
        $query->joinWith('metas');
        $query->andWhere(['OR',
                [$translation_table . '.language' => \Yii::$app->language],
                isset(\Yii::$app->params['fallbackLanguages'][\Yii::$app->language]) ? [$translation_table . '.language' => \Yii::$app->params['fallbackLanguages'][\Yii::$app->language]] : '']
        );
        $query->andWhere(['OR',
                [$meta_table . '.language' => \Yii::$app->language],
                isset(\Yii::$app->params['fallbackLanguages'][\Yii::$app->language]) ? [$meta_table . '.language' => \Yii::$app->params['fallbackLanguages'][\Yii::$app->language]] : '']
        );

        $query->groupBy([$model_table . '.id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
//                    'title' => [
//                        'asc' => [$translation_table . '.title' => SORT_ASC],
//                        'desc' => [$translation_table . '.title' => SORT_DESC]
//                    ],
//                    'release_date' => [
//                        'asc' => [$meta_table . '.release_date' => SORT_ASC],
//                        'desc' => [$meta_table . '.release_date' => SORT_DESC]
//                    ],
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
        $query->andFilterWhere(['LIKE', $translation_table . '.title', $this->title]);

        if (!empty($this->release_date)) {
            $query->andFilterWhere([$meta_table . '.release_date' => date('Y-m-d H:i:s', strtotime($this->release_date))]);
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
