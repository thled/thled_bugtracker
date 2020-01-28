#!/bin/bash
docker exec -i thled_bugtracker_app_1 /usr/src/app/vendor/bin/phpcs "$@"
