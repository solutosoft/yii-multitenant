<?php

namespace solutosoft\multitenant\Tests;


use Yii;
use yii\base\NotSupportedException;
use yii\base\UnknownMethodException;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\test\FixtureTrait;
use yii\test\BaseActiveFixture;

/**
 * This is the base class for all unit tests.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    use FixtureTrait;

    /**
     * This method is called before the first test of this test class is run.
     * Attempts to load vendor autoloader.
     * @throws \yii\base\NotSupportedException
     */
    public static function setUpBeforeClass()
    {
        $vendorDir = __DIR__ . '/../vendor';
        $vendorAutoload = $vendorDir . '/autoload.php';
        if (file_exists($vendorAutoload)) {
            require_once($vendorAutoload);
        } else {
            throw new NotSupportedException("Vendor autoload file '{$vendorAutoload}' is missing.");
        }
        require_once($vendorDir . '/yiisoft/yii2/Yii.php');
        Yii::setAlias('@vendor', $vendorDir);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->unloadFixtures();
        $this->destroyApplication();
        parent::tearDown();
    }

    /**
     * Destroys the application instance created by [[mockApplication]].
     */
    protected function destroyApplication()
    {
        if (\Yii::$app) {
            if (\Yii::$app->has('session', true)) {
                \Yii::$app->session->close();
            }
            if (\Yii::$app->has('db', true)) {
                Yii::$app->db->close();
            }
        }
        Yii::$app = null;
        Yii::$container = new Container();
    }

    /**
     * Populates Yii::$app with a new application
     * @param array $extra
     * @return void
     */
    protected function mockApplication($extra = [])
    {
        $config  = ArrayHelper::merge([
            'class' => 'yii\web\Application',
            'id' => 'yii-multitenant-web-test',
            'basePath' => dirname(__DIR__) . '/../',
            'vendorPath' => __DIR__ . '/../../vendor',
            'timeZone' => 'UTC',
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
                'user' => [
                    'identityClass' => 'solutosoft\multitenant\tests\models\Person',
                    'authTimeout' => 10,
                    'enableSession' => false,
                ],
                'request' => [
                    'cookieValidationKey' => 'TPweYCnTCow7EwZlCkjOYsSL',
                    'scriptFile' => __DIR__ .'/index.php',
                    'scriptUrl' => '/index.php',
                ],
            ]
        ], $extra);

        Yii::createObject($config);

        $this->setupDatabase();
        $this->initFixtures();
    }

    protected function setupDatabase()
    {
        $db = Yii::$app->getDb();

        $db->createCommand()->createTable('person', [
            'id' => 'pk',
            'name' => 'string',
            'profile_id' => 'integeger',
            'tenant_id' => 'integer'
        ])->execute();

        $db->createCommand()->createTable('profile', [
            'id' => 'pk',
            'number' => 'string',
            'description' => 'string'
        ])->execute();
    }

    /**
     * Authorizes user on a site without submitting login form.
     * @param int $user
     * @return void
     */
    public function loggedInAs($user)
    {
        $identityClass = Yii::$app->user->identityClass;
        $identity = call_user_func([$identityClass, 'findIdentity'], $user);
        Yii::$app->user->login($identity);
    }


    /**
     * Calls the named method which is not a class method.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when an unknown method is being invoked.
     * @param string $name the method name
     * @param array $params method parameters
     * @throws UnknownMethodException when calling unknown method
     * @return mixed the method return value
     */
    public function __call($name, $params)
    {
        $fixture = $this->getFixture($name);
        if ($fixture instanceof BaseActiveFixture) {
            return $fixture->getModel(reset($params));
        } else {
            throw new UnknownMethodException('Unknown method: ' . get_class($this) . "::$name()");
        }
    }
}
