#!/bin/bash

function get_milestone() {
	curl --silent "https://api.github.com/repos/${GITHUB_REPOSITORY}/milestones" |
		grep '"number":' |
		sed -E 's/.*: ([^,]+).*/\1/'
}

MILESTONE=$(get_milestone)

curl --request PATCH \
  --url https://api.github.com/repos/${GITHUB_REPOSITORY}/milestones/${MILESTONE} \
  --header "Authorization: Bearer ${GITHUB_TOKEN}" \
  --header 'Content-Type: application/json' \
  --data '{"state":"closed"}'
