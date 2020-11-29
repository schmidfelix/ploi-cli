---
title: Pushing local changes
weight: 2
---

# Pushing your local .env file to ploi

With `ploi env:push` you can push your local .env file to ploi.

By default it will use the `.env.ploi` file, but you can customize that by providing the
`--filename` parameter.

After pushing your environment file, the script will ask you to delete your local version
to ensure that you do not accidentally keep an old state of your environment file.
