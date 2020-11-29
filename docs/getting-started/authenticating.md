---
title: Authentication
weight: 2
---

# Authentication

Before you can link your projects to ploi provisioned servers, you have to save your ploi api token. You can
get one there: [https://ploi.io/profile/api-keys](https://ploi.io/profile/api-keys)

To save your token simply run:

```shell script
ploi token <token?> {--force?}
```

When not entering a token as argument, the script will ask you after it. The token will get stored in your home directory: `~/.ploi/config.php`

When you have already a token set, please use `--force`, otherwise the script will warn you, that there's already a token set.


Now you're ready to go, to [link your first site](/docs/ploi-cli/v1/basic-commands/init).
