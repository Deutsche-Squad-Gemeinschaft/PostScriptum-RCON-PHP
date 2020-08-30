#!/bin/bash
timeout --signal=SIGINT 600 docker logs -f squad-server 2>&1 | grep -qe "LogOnlineSession"

if [ $? == 1 ]; then
    echo "Server startup did timeout."
    exit 1
fi