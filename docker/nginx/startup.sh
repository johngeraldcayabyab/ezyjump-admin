#!/bin/bash

# cron job to restart nginx every 6 hour
(crontab -l ; echo "0 0 */4 * * nginx -s reload") | crontab -

# Start crond in background
crond -l 2 -b

#* * * * * root nginx -s reload >> /var/log/cron.log

# Start nginx in foreground
echo "NGINX started, daemon will restart every 6 hours now.";
nginx

