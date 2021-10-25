## Spydroid backup script

A simple way to backup spydroid videos from your android device to
local storage. Put it into crontab or launch manually.

### Requirements: 

- php-curl

### Usage

    php spydroid-backup.php -u <archive-url> -d <backup-directory>
    
Url must be something like:

    http://192.168.1.101:8080/list_videos
