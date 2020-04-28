#!/usr/bin/env bash
if ! [[ -d ./data ]]; then
    mkdir -p ./data
fi

if ! [[ -d ./data/mysql ]]; then
    mkdir -p ./data/mysql
fi

sudo docker-compose up --build
