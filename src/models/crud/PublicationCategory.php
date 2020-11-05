<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\prototype\models\Html;
use dmstr\modules\publication\models\crud\base\PublicationCategory as BasePublicationCategory;
use dosamigos\translateable\TranslateableBehavior;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Yii;
use yii\base\InvalidConfigException;
use yii\twig\ViewRenderer;

/**
 * This is the model class for table "{{%dmstr_publication_category}}".
 *
 * @property-read string|mixed $label
 * @property string title
 */
class PublicationCategory extends BasePublicationCategory
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_category}}';
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return \yii\helpers\Html::encode($this->title) . ($this->isNewRecord ? '' : ' (#' . $this->id.')');
    }

    /**
     * @param array $properties
     * @param $teaser
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigException
     * @return null|string
     */
    public function render(array $properties, $teaser)
    {
        $widgetTemplate = $teaser !== true ? $this->contentWidgetTemplate : $this->teaserWidgetTemplate;

        $appTwig = (array)Yii::$app->view->renderers['twig'];

        /** @var ViewRenderer $twigRenderer */
        $twigRenderer = Yii::createObject([
            'class' => ViewRenderer::class,
            'functions' => $appTwig['functions'],
            'globals' => $appTwig['globals']
        ]);

        $twigRenderer->twig->setLoader(new ArrayLoader([
            'publication' => $widgetTemplate->twig_template,
        ]));
        $twig = $twigRenderer->twig;

        return $twig->render('publication', $properties);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['translatable'] = [
            'class' => TranslateableBehavior::class,
            'relation' => 'translations',
            'translationAttributes' => [
                'title'
            ],
            'deleteEvent' => ActiveRecord::EVENT_BEFORE_DELETE,
            'restrictDeletion' => TranslateableBehavior::DELETE_LAST
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['crud'] = [
            'title',
            'content_widget_template_id',
            'teaser_widget_template_id'
        ];
        return $scenarios;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['stringLengthAttribute'] = ['title', 'string', 'max' => 255];
        $rules['requiredAttributes'] = ['ref_lang', 'required'];
        return $rules;
    }
}
