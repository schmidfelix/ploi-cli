---
title: Pushing local changes
weight: 2
---

# Pushing your local deploy script to ploi

With `ploi deploy:push` you can push your local deploy script to ploi.

By default it will use the `deploy.sh` file, but you can customize that by providing the
`--filename` parameter.

After pushing your deploy script, the script will ask you to delete your local version
to ensure that you do not accidentally keep an old state of your deployment script.
