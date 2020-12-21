#!/bin/bash

JSON=`curl --silent "https://api.github.com/repos/${GITHUB_REPOSITORY}/milestones"`
MILESTONE_NUMBER=`echo "$JSON" | grep '"number":' | sed -E 's/.*: ([^,]+).*/\1/'`
MILESTONE_NAME=`echo "$JSON" | grep '"title":' | sed -E 's/.*: "([^"]+).*/\1/'`

cat >"${GITHUB_ENV}" <<EOF
MILESTONE_NUMBER=${MILESTONE_NUMBER}
MILESTONE_NAME=${MILESTONE_NAME}
EOF

echo "Set environment vars: ${MILESTONE_NUMBER} and ${MILESTONE_NAME}"
