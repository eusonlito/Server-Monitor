### Requirements

- PHP 8.2 or higher (bcmath bz2 intl mbstring opcache pcntl redis sqlite3 xsl zip)
- Redis

### Install

1. Clone the repository.

```bash
git clone https://github.com/eusonlito/Server-Monitor.git
```

2. Launch the setup

```bash
./composer setup
```

3. Edit the `.env` file and fill in the necessary variables.

```bash
vi .env
```

4. Launch the deploy

```bash
./composer deploy
```

5. Configure the cron job for the user related to the project.

```
* * * * * cd /var/www/monitor.domain.com && install -d storage/logs/artisan/$(date +"\%Y/\%m/\%d") && /usr/bin/php artisan schedule:run >> storage/logs/artisan/$(date +"\%Y/\%m/\%d")/schedule-run.log 2>&1
```

6. Create the main user.

```bash
php artisan user:create --email=user@domain.com --name=Admin --password=StrongPassword2 --enabled
```

7. Configure the web server `DocumentRoot` to `/var/www/project/public`.

8. Create your first server.

9. Connect to remote server and download the client script (never as root).

```bash
curl -H "Authorization: Bearer AUTH_TOKEN" \
  -o server-monitor.sh \
  https://monitor.domain.com/server/script

chmod 755 server-monitor.sh
```

10. Verify that the script matches the one at https://github.com/eusonlito/Server-Monitor/blob/master/resources/app/server/script

11. Test the script (never as root).

```bash
./server-monitor.sh
```

12. Add the script to cron jobs (never as root).

```
* * * * * cd /script/path && ./server-monitor.sh >> server-monitor.log 2>&1
```

### Upgrade

Updating the platform can be done in a simple way with the `./composer deploy` command executed by the user who manages that project (usually `www-data`).

# Screenshots

![screencapture-server-monitor-01](https://github.com/user-attachments/assets/efb87670-3c70-43e0-b197-75ef6f4aa8a9)
![screencapture-server-monitor-02](https://github.com/user-attachments/assets/f544d0c3-a3ac-41a5-9663-fe8df75ddd13)
![screencapture-server-monitor-03](https://github.com/user-attachments/assets/e1729b8a-6125-4c95-83fc-d676cd6a5132)
![screencapture-server-monitor-04](https://github.com/user-attachments/assets/1abe2917-f2a1-41a9-bbf4-407ac25b7f3c)
![screencapture-server-monitor-05](https://github.com/user-attachments/assets/1536b162-2baf-4e07-969c-1fcfeedc97f1)
![screencapture-server-monitor-06](https://github.com/user-attachments/assets/8ffa584f-3d19-44b4-a576-58dcaf30557b)

