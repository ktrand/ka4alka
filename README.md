# Установка и запуск проекта

## Шаги для настройки

1. **Создайте файл `.env`** в корневой папке проекта и в папке `backend`.
   
   ```bash
   touch .env
   touch backend/.env
   ```

2. **Скопируйте содержимое** файла `.env.example` из соответствующих папок в только что созданные файлы `.env`.

   ```bash
   cp .env.example .env
   cp backend/.env.example backend/.env
   ```

3. **Запустите Docker Compose** для поднятия всех сервисов.

   ```bash
   docker-compose up
   ```

4. **Перейдите в контейнер `symfony_app`** и установите зависимости с помощью Composer.

   ```bash
   docker exec -it symfony_app bash
   composer install
   ```
5. **Запустите миграции** внутри контейнера `symfony_app`.

   ```bash
   docker exec -it symfony_app bash
   php bin/console doctrine:migrations:migrate

Теперь ваш проект готов к использованию!
