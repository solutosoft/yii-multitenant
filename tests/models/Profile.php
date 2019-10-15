<?php

namespace solutosoft\multitenant\tests\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $number
 * @property string $description
 * @property int $tenant_id
 */
class Profile extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'number'], 'string']
        ];
    }
}
