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

2. Edit the `.env` file and fill in the necessary variables.

```bash
vi .env
```

3. Launch the deploy

```bash
./composer deploy
```

4. Configure the cron job for the user related to the project.

```
* * * * * cd /var/www/monitor.domain.com && install -d storage/logs/artisan/$(date +"\%Y/\%m/\%d") && /usr/bin/php artisan schedule:run >> storage/logs/artisan/$(date +"\%Y/\%m/\%d")/schedule-run.log 2>&1
```

5. Create the main user.

```bash
php artisan user:create --email=user@domain.com --name=Admin --password=StrongPassword2 --enabled
```

6. Configure the web server `DocumentRoot` to `/var/www/project/public`.

7. Create your first server.

8. Connect to remote server and download the client script (never as root).

```bash
curl -H "Authorization: Bearer AUTH_TOKEN" \
  -o server-monitor.sh \
  https://monitor.domain.com/server/script

chmod 755 server-monitor.sh
```

9. Verify that the script matches the one at https://github.com/eusonlito/Server-Monitor/blob/master/resources/app/server/script

10. Test the script (never as root).

```bash
./server-monitor.sh
```

11. Add the script to cron jobs (never as root).

```
* * * * * cd /script/path && ./server-monitor.sh >> server-monitor.log 2>&1
```

### Upgrade

Updating the platform can be done in a simple way with the `./composer deploy` command executed by the user who manages that project (usually `www-data`).

# Screenshots

![screencapture-server-monitor-01](https://github.com/user-attachments/assets/e908630f-8e59-4fcf-845b-a9827d3d91ac)
![screencapture-server-monitor-02](https://github.com/user-attachments/assets/37c7b70e-3d34-4818-a909-10af54f73c89)
![screencapture-server-monitor-03](https://github.com/user-attachments/assets/c250ca17-9a6e-4ece-b29a-a2f45b919621)
![screencapture-server-monitor-04](https://github.com/user-attachments/assets/7002327a-9064-414d-b78a-f1e6574980ec)
![screencapture-server-monitor-05](https://github.com/user-attachments/assets/2b529b6e-e2ab-4e5f-8b59-d81f3f1f460c)
![screencapture-server-monitor-06](https://github.com/user-attachments/assets/249d6ce0-8672-4efa-9ebe-c6826388077a)
