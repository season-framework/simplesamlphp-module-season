<?php

namespace SimpleSAML\Module\season\Auth\Source;

use SimpleSAML\Error;

class AdminAuth extends \SimpleSAML\Module\core\Auth\UserPassBase
{
    private $dsn;
    private $username;
    private $password;

    public function __construct($info, $config)
    {
        assert(is_array($info));
        assert(is_array($config));

        parent::__construct($info, $config);

        if (!is_string($config['dsn'])) {
            throw new \Exception('Missing or invalid dsn option in config.');
        }
        $this->dsn = $config['dsn'];
        if (!is_string($config['username'])) {
            throw new \Exception('Missing or invalid username option in config.');
        }
        $this->username = $config['username'];
        if (!is_string($config['password'])) {
            throw new \Exception('Missing or invalid password option in config.');
        }
        $this->password = $config['password'];
    }

    protected function login($username, $password)
    {
        assert(is_string($username));
        assert(is_string($password));

        $db = new \PDO($this->dsn, $this->username, $this->password);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->exec("SET NAMES 'utf8'");

        $st = $db->prepare('SELECT * FROM users WHERE id=:id AND password=password(:password)');
        if (!$st->execute(array('id' => $username, 'password' => $password))) {
            throw new \Exception('Failed to query database for user.');
        }

        $row = $st->fetch(\PDO::FETCH_ASSOC);

        if (!$row || count($row) == 0) {
            throw new Error\Error('WRONGUSERPASS');
        }

        if ($row['role'] != "admin") {
            throw new Error\Error('USERABORTED');
        }


        $attributes = array();

        // standard
        $attributes['uid'] = array($row['id']);
        $attributes['mail'] = array($row['id'] . "@season.co.kr");
        $attributes['mobile'] = array($row['mobile']);
        $attributes['role'] = array($row['role']);
        $attributes['company'] = array($row['company_id']);

        return $attributes;
    }
}
