<?php

namespace solutosoft\multitenant\tests\models;

use solutosoft\multitenant\MultiTenantRecord;
use yii\db\ActiveRecord;

/**
 * @property string $description
 * @property string $link
 */
class Tag extends ActiveRecord
{
    use MultiTenantRecord;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['description'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function resourceKey()
    {
        return 'link';
    }
}
