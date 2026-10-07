<?php

namespace App;

use Predis\Client;

class RedisExample
{
    private Client $client;

    public function __construct()
    {
        // 'redis' — имя сервиса в docker-compose
        $this->client = new Client('tcp://redis:6379');
    }

    public function setValue($key, $value)
    {
        $this->client->set($key, $value);
    }

    public function getValue($key)
    {
        return $this->client->get($key);
    }

    // Тема варианта 1: пользователи (хранятся в Redis как хеши)
    public function addUser(int $id, string $name, string $email): void
    {
        $this->client->hmset("user:$id", ['name' => $name, 'email' => $email]);
        $this->client->sadd('users', [$id]);
    }

    public function getUser(int $id): array
    {
        return $this->client->hgetall("user:$id");
    }

    public function getAllUsers(): array
    {
        $users = [];
        $ids = $this->client->smembers('users');
        sort($ids, SORT_NUMERIC);
        foreach ($ids as $id) {
            $users[$id] = $this->getUser((int)$id);
        }
        return $users;
    }
}
