# Symfony User Auth API

Проект реализует API для регистрации и авторизации пользователей по номеру телефона.

## Состав проекта

- Symfony 7.2 (PHP 8.3)
- PostgreSQL (через Docker)
- Redis (через Docker)
- Docker-контейнер для PHP с нужными расширениями

# Установка и запуск
Symfony и Docker-папки должны лежать на одном уровне:
```
- docker-symfony-auth/
- symfony-auth/
```


## Порядок установки

### 1. Развернуть Docker-инфраструктуру

- #### 1.1 Клонировать Docker-репозиторий:

```bash
git clone git@github.com:ankassi/docker-symfony-auth.git
cd symfony-auth-docker
docker compose up -d

```

- #### 1.2 В .env прописать подключение к базе данных
- #### 1.3 Запустить Docker
```bash
docker compose up -d
```

### 2. Развернуть Symfony-проект

#### 2.1 Установить проект
```bash
git clone git@github.com:ankassi/symfony-auth.git
```
#### 2.2 Прописать зависимости в .env
- Подключение к БД
- DATABASE_URL=pgsql://{symfony}:{password}@database:5432/{user_auth_db}
- REDIS_URL=redis://my_redis:6379


Подключение к БД должно быть таким же, как и в Docker
#### 2.3 Установить зависимости
```bash
composer install
```

### 3 Создать и применить миграцию
Для этого вернуться в Docker директорию

Создаём и применяем
```bash
docker compose exec php php bin/console make:migration
docker compose exec php php bin/console doctrine:migrations:migrate
```

