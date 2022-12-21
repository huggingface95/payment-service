#!/bin/bash

  blackList="var_dump\|dd("
  result=0
              RESULT=$(grep --include=\*.{php,js} --exclude-dir={vendor} -rnw './api' -e "var_dump\|dd(")
              if [[ ! -z $RESULT ]]; then
                  echo "$FILE contains denied word: $RESULT"
                  result=1
              fi

  if [ $result -ne 0 ]; then
      echo "Aborting build due to denied words"
      exit $result
  fi