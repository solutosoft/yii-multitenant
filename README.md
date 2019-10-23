Active Record MultiTenant Extension
=====================================

This extension provides support for ActiveRecord MultiTenant.

[![Build Status](https://travis-ci.org/solutosoft/yii-multitenant.svg?branch=master)](https://travis-ci.org/solutosoft/yii-multitenant)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/solutosoft/yii-multitenant/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/solutosoft/yii-multitenant/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/solutosoft/yii-multitenant/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/solutosoft/yii-multitenant/?branch=master)
[![Total Downloads](https://poser.pugx.org/solutosoft/yii-multitenant/downloads.png)](https://packagist.org/packages/yii2tech/ar-softdelete)
[![Latest Stable Version](https://poser.pugx.org/solutosoft/yii-multitenant/v/stable.png)](https://packagist.org/packages/yii2tech/ar-softdelete)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist solutosoft/yii-multitenant
```

or add

```json
"solutosoft/yii-multitenant": "*"
```

Usage
-----

1. Creates table with `tenant_id` column:

```php
class m191023_101232_create_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('post', [
            'id' =>  $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'category_id' => $this->integer(),
            'content' => $this->string()            
            'tenant_id' => $this->integer(),
        ]);
        
        $this->createTable('category', [
            'id' =>  $this->primaryKey(),
            'name' => $this->string()->notNull(),            
            'tenant_id' => $this->integer(),
        ]);
    }
}

```
2. Adds `TenantInterface` to user model:

```php
use yii\db\ActiveRecord;

class User extends ActiveRecord implements IdentityInterface, TenantInterface    
{
    **
     * {@inheritdoc}
     */
    public function getTenantId()
    {
        return // logic to determine tenant from current user
    }
    
    ...
    
}
```

3. Extends models with `tenant_id` attribute from `MultiTenantRecord` intead of `ActiveRecord`: 

```php
use solutosoft\multitenant\MultiTenantRecord;

class Post extends MultiTenantRecord
{    
    ...   
}

class Category extends MultiTenantRecord
{    
    ...   
}
```

Now when you save or execute some query the `tenant_id` column will be used. Example:

```php
// It's necessary the user will be logged in

$posts = \app\models\Post::find()->where(['category_id' => 1])->all();
// SELECT * FROM `post` WHERE `category_id` = 1 and `tenant_id` = 1;

$category = \app\models\Category([
  'name' => 'framework'
]);
$category->save();
// INSERT INTO `category` (`name`, `tenant_id`) values ('framework', 1);


