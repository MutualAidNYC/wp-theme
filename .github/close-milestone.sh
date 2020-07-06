#!/bin/bash

source .github/milestone-utils.sh

get_milestone()

curl --request PATCH \
  --url https://api.github.com/repos/${GITHUB_REPOSITORY}/milestones/${MILESTONE_NUMBER} \
  --header "Authorization: Bearer ${GITHUB_TOKEN}" \
  --header 'Content-Type: application/json' \
  --data '{"state":"closed"}'
