<?php

namespace nohnaimer\mattermost;

use Pimple\Container;
use yii\base\BaseObject;
use Gnello\Mattermost\Driver;
use yii\base\InvalidConfigException;

/**
 * Class Connection
 * @package nohnaimer\mattermost
 */
class Connection extends BaseObject
{
    /**
     * Example:
     * login and password auth:
     * [
     *      'scheme' => 'https',
     *      'basePath' => '/api/v4',
     *      'url' => 'your_chat_url',
     *      'login_id' => 'your_login_id',
     *      'password' => 'your_password',
     * ]
     * token auth:
     * [
     *      'scheme' => 'https',
     *      'basePath' => '/api/v4',
     *      'url' => 'your_chat_url',
     *      'token' => 'your_token',
     * ]
     * @var array
     * @see https://github.com/gnello/php-mattermost-driver
     */
    public $driverOptions = [];
    /**
     * @var array
     * @see https://docs.guzzlephp.org/en/stable/request-options.html
     */
    public $guzzleOptions = [];
    /**
     * @var Driver
     */
    private $connection;

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->driverOptions)) {
            throw new InvalidConfigException('The "driverOptions" property must be set.');
        }
    }

    /**
     * @return Driver|null
     * @throws InvalidConfigException
     */
    public function initConnection()
    {
        if ($this->connection instanceof Driver) {
            return $this->connection;
        }

        $config['driver'] = $this->driverOptions;

        if (empty($this->guzzleOptions) === false) {
            $config['guzzle'] = $this->guzzleOptions;
        }

        $container = new Container($config);
        $connection = new Driver($container);

        $response = $connection->authenticate();

        if ($response->getStatusCode() !== 200) {
            throw new InvalidConfigException($response->getBody());
        }

        return $this->connection = $connection;
    }
}