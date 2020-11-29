---
title: Creating a server
weight: 1
---

# Creating a server

The `ploi server:create` command allows you to create and set up a new server for your next project.

First, the script will ask you after a name for your new server. This name will be used in ploi and
your server provider. Choose a unique one.

Then an interactive menu will open, asking you which server provider credentials you want to use.
![](/assets/docs/ploi-cli/v1/servers/provider.png)

After selecting one, another menu will open, asking you which plan the new server should be on.
![](/assets/docs/ploi-cli/v1/servers/plan.png)

Next you have to select the location for your server. Choose the one that is the nearliest to you
or your client.

The script will ask you about the server type for your new server. You can choose between:

- server
- load-balancer
- database-server
- redis-server

When choosing `server` or `database-server` you will get asked which database system you want to
install. Available types are: `none`, `mysql`, `mariadb`, and `postgresql`.

When you have chosen `server` the script will also ask after you preferred webserver system.
You can choose between `nginx` or `nginx-apache`. It will also ask you after your preferred php
version. Default is `7.1`

Then your new server is getting created, and when finished, you will get an email from ploi.
Then you're ready to go to [link your first site](/docs/ploi-cli/v1/basic-commands/init).
