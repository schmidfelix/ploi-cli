---
title: Pulling .env file
weight: 1
---

# Pulling the .env file from ploi

The `ploi env:pull` command pulls the current .env file on your remote server to
your local filesystem.

You can provide a filname for the pulled file by passing the `--filname` parameter.
The default is `.env.ploi`

```shell script
ploi env:pull --filename=.env.remote
```
When running this command, the environment file will get saved to `.env.remote`

> Make sure to add `.env.ploi` to your `.gitignore`
