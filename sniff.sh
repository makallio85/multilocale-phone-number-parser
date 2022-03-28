#!/bin/bash

# Make sure this file is executable
# chmod +x sniff

if [ `echo "$@" | grep '\-\-fix'` ] || [ `echo "$@" | grep '\-f'` ]; then
    FIX=1
else
    FIX=0
fi

if [ "$FIX" = 1 ]; then
	# Sniff and fix
	vendor/bin/phpcbf src/ --standard=PSR2
	vendor/bin/phpcbf tests/ --standard=PSR2
else
	# Sniff only
	vendor/bin/phpcs src/ --standard=PSR2
fi
