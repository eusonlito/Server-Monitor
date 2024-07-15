### Requirements

- PHP 8.2 or higher (bcmath bz2 intl mbstring opcache pcntl redis sqlite3 xsl zip)
- Redis

### Install

1. Clone the repository.

```bash
git clone https://github.com/eusonlito/Server-Monitor.git
```

2. Copy the `.env.example` file as `.env` and fill in the necessary variables.

```bash
cp .env.example .env
```

3. Create the SQLite database.

```bash
touch storage/database/database.sqlite
```

4. Install composer dependencies (remember that we always use the PHP 8.2 binary).

```bash
./composer install --no-dev --optimize-autoloader --classmap-authoritative --ansi
```

5. Generate the application key.

```bash
php artisan key:generate
```

6. Launch the deploy

```bash
./composer deploy
```

7. Configure the cron job for the user related to the project.

```
* * * * * cd /var/www/monitor.domain.com && install -d storage/logs/artisan/$(date +"\%Y/\%m/\%d") && /usr/bin/php artisan schedule:run >> storage/logs/artisan/$(date +"\%Y/\%m/\%d")/schedule-run.log 2>&1
```

8. Create the main user.

```bash
php artisan user:create --email=user@domain.com --name=Admin --password=StrongPassword2 --enabled
```

9. Configure the web server `DocumentRoot` to `/var/www/project/public`.

10. Create your first server.

11. Connect to remote server and download the client script (never as root).

```bash
curl -H "Authorization: Bearer AUTH_TOKEN" \
  -o server-monitor.sh \
  https://monitor.domain.com/server/script

chmod 755 server-monitor.sh
```

13. Verify that the script matches the one at https://github.com/eusonlito/Server-Monitor/blob/master/resources/app/server/script

14. Test the script (never as root).

```bash
./server-monitor.sh
```

15. Add the script to cron jobs (never as root).

```
* * * * * cd /script/path && ./server-monitor.sh >> server-monitor.log 2>&1
```

### Upgrade

Updating the platform can be done in a simple way with the `./composer deploy` command executed by the user who manages that project (usually `www-data`).
