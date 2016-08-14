#!/bin/sh

for file in `find . -type f | grep -E '\.php$'`
do
    php -l "$file"
done
