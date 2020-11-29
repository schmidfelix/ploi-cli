---
title: Linking projects with sites
weight: 1
---

# Init

> Requirement for linking a project to ploi, is that your working directory is a git repository.

The `ploi init` command allows you to start the ploi.io site provisioning process.

When calling `ploi init`, you can choose from an interactive list, which server you want to link the site with.
![](/assets/docs/ploi-cli/v1/basic-commands/select-server.png)

Then you're getting asked if you want to create a new site on that server, or to link your repository with an already
existing site.

After confirming the domain, you're getting asked which Repository-Driver is used for your project.
Available ones are `github`, `bitbucket`, `gitlab` and `custom`.

The script now tries to auto-detect your current repository url, and asks you to confirm it.

**IMPORTANT:** When using a provider else than `custom` you have to specify your repo in short form, without
the hole url. Example: `schmidfelix/example-app`. When using the `custom` driver, specify your URL completely, example:
`git@github.com:schmidfelix/example-app.git`

You have to confirm your current branch, and your project is linked! It will also automatically active a test domain,
which gets printed after creating the site.
