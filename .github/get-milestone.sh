#!/bin/bash

JSON=`curl --silent "https://api.github.com/repos/${GITHUB_REPOSITORY}/milestones"`
MILESTONE_NUMBER=`echo "$JSON" | grep '"number":' | sed -E 's/.*: ([^,]+).*/\1/'`
MILESTONE_NAME=`echo "$JSON" | grep '"title":' | sed -E 's/.*: "([^"]+).*/\1/'`

echo "::set-env name=MILESTONE_NUMBER::${MILESTONE_NUMBER}"
echo "::set-env name=MILESTONE_NAME::${MILESTONE_NAME}"

echo "Set environment vars: ${MILESTONE_NUMBER} and ${MILESTONE_NAME}
