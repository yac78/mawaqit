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

#i=0
#while ! wget -q --spider --timeout=2 $url; do
#  sleep 1
#  i=$(( $i + 1 ))
#  if (( $i == 3 )); then
#    url=http://mawaqit.local/mosquee
#    
#    # check if local server is responding
#    i=0
#    while ! wget -q --spider --timeout=1 $url; do
#      sleep 1
#      i=$(( $i + 1 ))
#      if (( $i == 30 )); then
#        break
#      fi
#    done
#    break
#  fi
#done

chromium-browser --app=$url --start-fullscreen --start-maximized
