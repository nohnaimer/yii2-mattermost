Yii2 Mattermost extension
====================
Yii2 extension for PHP Driver to interact with the [Mattermost Web Service API](https://about.mattermost.com/). 

Please read [the api documentation](https://api.mattermost.com/) for further information on using this application.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
php composer.phar require --prefer-dist nohnaimer/yii2-mattermost "0.1.*"
```

or add

```
"nohnaimer/yii2-mattermost": "0.1.*"
```

to the require section of your `composer.json` file.


Configuration
-----
Full configuration (login and password auth):
```php
[
    'components' => [
        'mattermost' => [
            'class' => nohnaimer\mattermost\Connection::class,
            'driverOptions' => [
                'scheme' => 'https',
                'basePath' => '/api/v4',
                'url' => 'localhost',
                'login_id' => 'login',
                'password' => 'password',
            ],
        ],
    ],
];
```
Token auth:

```php
[
    'components' => [
         'mattermost' => [
            'class' => nohnaimer\mattermost\Connection::class,
            'driverOptions' => [
                'url' => 'localhost',
                'token' => 'token',
            ],
        ],
    ],
];
```
Usage
-----
Authentication:
```php
$mattermost = Yii::$app->mattermost->initConnection();
```

### Users endpoint
```php
//Add a new user
$result = $mattermost->getUserModel()->createUser([
    'email'    => 'test@test.com', 
    'username' => 'test', 
    'password' => 'testpsw'
]);

//Get a user
$result = $mattermost->getUserModel()->getUserByUsername('username');

//Please read the UserModel class or refer to the api documentation for a complete list of available methods.
```

### Channels endpoint
```php
//Create a channel
$result = $mattermost->getChannelModel()->createChannel([
    'name'         => 'new_channel',
    'display_name' => 'New Channel',
    'type'         => 'O',
]);


//Get a channel
$result = $mattermost->getChannelModel()->getChannelByName('team_id_of_the_channel_to_return', 'new_channel');

//Search a channel
$result = $mattermost->getChannelModel()->searchChannels($teamId, [
    'term' => "full or partial name or display name of channels"
]);

//Please read the ChannelModel class or refer to the api documentation for a complete list of available methods.
```

### Posts endpoint
```php
//Create a post
$result = $mattermost->getPostModel()->createPost([
    'channel_id' => 'The channel ID to post in',
    'message' => 'The message contents, can be formatted with Markdown',
]);


//Get a post
$result = $mattermost->getPostModel()->getPost('post_id_of_the_post_to_return');

//Please read the PostModel class or refer to the api documentation for a complete list of available methods.
```

### Files endpoint
```php
//Upload a file
$result = $mattermost->getFileModel()->uploadFile([
    'channel_id' => 'The ID of the channel that this file will be uploaded to',
    'filename' => 'The name of the file to be uploaded',
    'files' => fopen('Path of the file to be uploaded', 'rb'),
]);

//Send a post with the file just uploaded
$result = $mattermost->getPostModel()->createPost([
    'channel_id' => 'The channel ID to post in',
    'messages' => 'The message contents, can be formatted with Markdown',
    'file_ids' => 'A list of file IDs to associate with the post',
]);

//Please read the FileModel class or refer to the api documentation for a complete list of available methods.
```

### Preferences endpoint
```php
//Get a list of the user's preferences
$result = $mattermost->getPreferenceModel('user_id')->getUserPreference();

//Please read the PreferenceModel class or refer to the api documentation for a complete list of available methods.
```