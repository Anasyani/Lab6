# Лабораторная работа №6: Redis, Elasticsearch, ClickHouse

## 👩‍💻 Автор

ФИО: Янина Анастасия Алексеевна  
Группа: 2
Вариант: 1 

---

## 📌 Описание задания

Изучить нереляционные БД (Redis, Elasticsearch, ClickHouse) и взаимодействовать с ними из PHP: Redis через библиотеку Predis, Elasticsearch и ClickHouse через HTTP API с помощью Guzzle.

---

## ⚙️ Как запустить проект

```
git clone <ссылка на репозиторий>
cd lab6
docker-compose up -d --build
docker-compose exec php composer install
```

Последняя команда нужна, чтобы папка `www/vendor` появилась и в смонтированной папке проекта.

Сайт: `http://localhost:8080`

Elasticsearch стартует около минуты и требует около 1–2 ГБ свободной памяти. Если в блоке Elasticsearch ошибка подключения, нужно подождать и обновить страницу.

---

## 📂 Содержимое проекта

- `docker-compose.yml` — сервисы `nginx`, `php`, `redis`, `elasticsearch`, `clickhouse`
- `Dockerfile` — PHP-FPM с Composer
- `nginx/default.conf` — конфиг Nginx с PHP-FPM
- `www/composer.json` — зависимости `guzzlehttp/guzzle`, `predis/predis` и автозагрузка
- `www/Helpers/ClientFactory.php` — фабрика клиентов Guzzle
- `www/RedisExample.php` — работа с Redis (ключи и пользователи как хеши)
- `www/ElasticExample.php` — индексация и поиск в Elasticsearch
- `www/ClickhouseExample.php` — SQL-запросы к ClickHouse по HTTP
- `www/index.php` — запуск примеров

---

## 🧪 Ход работы

1. **Docker.** В `docker-compose.yml` добавлены сервисы Redis, Elasticsearch и ClickHouse в общей сети `default_network`. Контейнеры обращаются друг к другу по именам сервисов (`redis`, `elasticsearch`, `clickhouse`).
2. **Composer.** Установлены `guzzlehttp/guzzle` и `predis/predis`, настроена PSR-4 автозагрузка пространства имён `App`.
3. **Redis.** Через Predis выполняются `set`/`get`. Для темы «Пользователи» каждый пользователь хранится как хеш `user:<id>` (имя, email), id собираются в множество `users`.
4. **Elasticsearch.** Через Guzzle выполняется индексация документа (`PUT books/_doc/1`) и поиск (`GET books/_search`).
5. **ClickHouse.** Через HTTP-интерфейс создаётся таблица `users`, добавляются записи и выполняется выборка.

---

## ✅ Результат

Страница `http://localhost:8080` выводит результаты работы с Redis, Elasticsearch и ClickHouse, в том числе список пользователей из Redis.
