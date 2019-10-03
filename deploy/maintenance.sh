#!/bin/bash

ssh -p 1983 -t mawaqit@$MAWAQIT_PROD_IP 'touch /var/www/mawaqit/repo/docker/data/maintenance'