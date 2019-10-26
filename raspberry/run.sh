#!/bin/bash

# online site
if [ -f ~/Desktop/online_site.txt ]; then
    url=`cat ~/Desktop/online_site.txt`
fi

i=0
while ! wget -q --spider --timeout=2 $url; do
  sleep 1
  ((i+=1))
  if (( $i == 5 )); then
    url=http://mawaqit.local/mosquee
    break;
  fi 
done

chromium-browser --app=$url --start-fullscreen --start-maximized
