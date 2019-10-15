<?php

namespace solutosoft\multitenant\tests\models;

use solutosoft\multitenant\MultiTenantRecord;
use solutosoft\multitenant\TenantInterface;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property string $firstName
 * @property string $lastName
 * @property string $birthDate
 * @property double $salary
 * @property integer $profile_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $tenant_id
 * @property Profile[] $profile
 * @property Tag[] $tags
 * @property Contact[] $contacts
 */
class Person extends ActiveRecord implements
    IdentityInterface,
    TenantInterface
{
    use MultiTenantRecord;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'profile_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('person_tag', ['person_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['profile_id'], 'integer']
        ];
    }

     /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'tags'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()
            ->withoutTenant()
            ->where(['id' => $id])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
    * @inheritdoc
    */
   public function getTenantId()
   {
       return ($this->tenant_id) ? $this->tenant_id : $this->id;
   }

}
