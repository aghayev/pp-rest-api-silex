#!/bin/bash

rsync -azP --chown imran:psacln ./{app,db,lib,templates,web} pleasepay.co.uk-as-root:/var/www/vhosts/pleasepay.co.uk/pp-api
