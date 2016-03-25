if ps -ef | grep -v grep | grep bot_bet_new.php ; then
        exit 0
else
		timeout 18000 /usr/local/bin/php -f /root/new/bot_bet_new.php
        exit 0
fi
