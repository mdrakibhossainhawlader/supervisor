# Supervisor

It's a PHP XML RPC Client for supervisor.

## Requirement
- PHP >= 5.6
- Zend-XMLRPC

## Install
`composer require rockxsj/supervisor`

## Usage
```php
$client = new \Rockxsj\Supervisor('http://127.0.0.1:9001/RPC2', 'username', 'password');
$processInfos = $client->getAllProcessInfo();
```

## Tips
If you use the phpstorm as your ide, you will get the method and params autocomplete.

You can check [http://supervisord.org/api.html](http://supervisord.org/api.html) this page for full documents.

## Chat with me

Feel free to make a issue when you think you get a bug.

Also, you can join the QQ group: 632109190 to chat with me.
