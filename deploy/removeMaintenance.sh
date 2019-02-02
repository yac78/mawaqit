#!/bin/bash

ssh -p $port mawaqit@$server 'bash -s' < deploy/maintenance.sh
