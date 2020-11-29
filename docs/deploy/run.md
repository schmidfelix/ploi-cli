---
title: Triggering a deployment
weight: 3
---

# Deploying on the remote server

To start a deployment on your remote server simply run `ploi deploy:run`.
It will start the deployment process.

## Printing the remote log

To print the local deployment log to your local console, simply add the `--log` option.

> Make sure to add `echo "Application deployed!"` to your deploy script. `Application deployed` is used
> as keyword to stop watching the logs.
