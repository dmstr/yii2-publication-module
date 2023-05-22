<?php

namespace dmstr\modules\publication\widgets;

use bedezign\yii2\audit\components\Version;
use bedezign\yii2\audit\models\AuditEntry;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Content widget to provide a simple and human-readable way to show the different version of the given model via audit
 * trails
 */
class ModelVersions extends Widget
{

    /**
     * Optional label to display
     * @var string|null
     */
    public ?string $label = null;
    /**
     * The model where we get the information from
     *
     * @var ActiveRecord|ActiveRecord[]|null
     */
    private $_model = null;

    /**
     * Config for a new widget
     * @var array
     */
    public array $relations = [];

    /**
     * List of versions indexed by audit trail entry id of the given model from $model
     *
     * @return array{auditEntry:AuditEntry|null, attributes: array}
     */
    protected function getVersions(): array
    {
        $models = $this->getModels();
        // Skip if $model is not set
        if (is_null($models)) {
            return [];
        }
        $versions = [];
        foreach ($models as $index => $model) {
            // Find all model versions by given $model
            $modelVersions = Version::versions($model::class, $model->getPrimaryKey());

            // Build a list of workable version infos
            foreach ($modelVersions as $entryId => $attributes) {
                $versions[$index][$entryId] = [
                    'auditEntry' => AuditEntry::findOne(['id' => $entryId]),
                    'attributes' => $attributes
                ];
            }
        }
        return $versions;
    }

    public function run()
    {
        return $this->render('model-versions', [
            'versions' => $this->getVersions(),
            'relations' => $this->relations,
            'label' => $this->label
        ]);
    }

    /**
     * @return ActiveRecord[]|null
     */
    protected function getModels(): ?array
    {
        if ($this->_model instanceof ActiveRecord) {
            return [$this->_model];
        }
        return $this->_model;
    }

    /**
     * Magic getter
     * @param ActiveRecord|ActiveRecord[] $model
     */
    public function setModel(ActiveRecord|array $model): void
    {
        $this->_model = $model;
    }
}
