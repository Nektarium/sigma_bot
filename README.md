# Sigma Bot

Sigma Bot — это веб-сервис для организации переписки между пользователем веб-приложения и анонимным гостем через Telegram-бота.

Гость отправляет сообщение через Telegram, оно сохраняется в базе данных, а пользователь веб-сервиса может просматривать чаты и отвечать на сообщения. Ответы отправляются обратно через Telegram API.

## Особенности проекта

### Прием сообщений от гостя
Сообщения, отправленные гостем через Telegram-бота, получаются посредством метода `getUpdates` Telegram API и сохраняются в базе данных.

### Просмотр и управление чатами
Пользователь веб-сервиса может видеть список чатов и просматривать все сообщения в выбранном чате.

### Отправка ответов гостю
При отправке ответа через веб-интерфейс сообщение сохраняется и отправляется гостю через метод `sendMessage` Telegram API.

### Контроль дублирования
Обновления Telegram фильтруются с помощью параметра `offset` (на основе `update_id`), а также проверяется уникальность сообщений через `telegram_message_id`, что предотвращает дублирование полученных сообщений.

### Автоматическое обновление
Проект настроен на запуск команды `telegram:fetch-updates` через Laravel Scheduler (каждую минуту), а также можно вызвать её вручную для получения обновлений.

## Стек технологий

* PHP
* Laravel
* MySQL
* Docker & Docker Compose
* PHPUnit
* Telegram Bot API
* Swagger для документации API

## Установка и запуск локального окружения

### 1. Предварительные требования

Убедитесь, что на вашей машине установлены:
* [Docker](https://docs.docker.com/get-docker/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* [Git](https://git-scm.com/)

### 2. Клонирование репозитория

```bash
git clone https://github.com/Nektarium/sigma_bot.git
cd sigma_bot
```

### 3. Настройка переменных окружения

1. Создайте файл `.env` на основе `.env.example`:
   ```bash
   cp .env.example .env
   ```

2. Откройте файл `.env` и укажите значения для переменных окружения:
   ```env
   # Сгенерируйте ключ приложения
   APP_KEY=base64:your-key-here

   # Настройки базы данных
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=sigma_db
   DB_USERNAME=sigma_user
   DB_PASSWORD=sigma_pass
   DB_ROOT_PASSWORD=secret_root_password

   # Настройки Telegram
   TELEGRAM_BOT_TOKEN=your_bot_token
   TELEGRAM_API_URL=https://api.telegram.org/bot
   ```

   > **Примечание**: Токен бота можно получить через [@BotFather](https://t.me/BotFather)

### 4. Запуск контейнеров

```bash
docker-compose up -d
```

### 5. Запуск миграций

```bash
docker-compose exec app php artisan migrate
```

### 6. Запуск тестов

```bash
docker-compose exec app php artisan test
```

### 7. Доступ к веб-интерфейсу

Откройте [http://localhost:8080/](http://localhost:8080/) в вашем браузере.

В веб-интерфейсе вы можете просматривать чаты и отвечать на сообщения.