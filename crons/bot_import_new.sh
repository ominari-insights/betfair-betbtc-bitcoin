if ps -ef | grep -v grep | grep bot_import_new.php ; then
        exit 0
else
		timeout 120 /usr/local/bin/php -f /root/new/bot_import_new.php
        exit 0
fi
