#!/bin/bash
timeout --signal=SIGINT 900 docker logs -f ps-server 2>&1 | grep -qe "LogInit:Display: Starting Game."

if [ $? == 1 ]; then
    echo "Server startup did timeout."
    docker logs ps-server
    exit 1
fi