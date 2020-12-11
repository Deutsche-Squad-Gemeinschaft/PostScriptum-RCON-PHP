#!/bin/bash
timeout --signal=SIGINT 1200 docker logs -f ps-server 2>&1 | grep -qe "LogGameState: Match State Changed from EnteringMap to WaitingToStart"

if [ $? == 1 ]; then
    echo "Server startup did timeout."
    docker logs ps-server
    exit 1
fi