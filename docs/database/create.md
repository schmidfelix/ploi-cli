---
title: Creating a database
weight: 1
---

# Creating a database

The `ploi db:create {name}` command creates a database for your site. 

The first argument should be the name of your database, otherwise the script will ask you for the name.

## Creating database with user

To create a database with a user, run:

```shell script
ploi db:create {name} --user=username
```

The script will ask you for a password for the user.

### Generating a random password

To generate a random password for the user run

```shell script
ploi db:create {name} -G|--generate-password
```

the script will automatically create a user with the same name as the database,
when no username is provided via the `--user` option.
