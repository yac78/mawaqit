#!/bin/bash

# online site
if [ -f ~/Desktop/online_site.txt ]; then
    url=`cat ~/Desktop/online_site.txt`
fi

sleep 10;

wget -q --spider $url

if [ $? -ne 0 ]; then
    url=http://mawaqit.local/mosquee
fi

chromium-browser --app=$url --start-fullscreen --start-maximized
