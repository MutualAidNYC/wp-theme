#!/bin/bash

ID=$1

curl --request PATCH \
  --url https://api.github.com/repos/${GITHUB_REPOSITORY}/issues/${ID} \
  --header "Authorization: Bearer ${GITHUB_TOKEN}" \
  --header 'Content-Type: application/json' \
  --data '{"milestone":${MILESTONE_NUMBER}}'
