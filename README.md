# laravel-package-helper
Provide commands to create laravel package files.

## Available Commands
---
Commands:

```bash
./vendor/bin/larapack config:install
```
Description:
will create config/larapack.php file update `package-namespace` variable as per your package namespace

```bash
./vendor/bin/larapack make:test testname --unit(optional)
```
Create a new test class