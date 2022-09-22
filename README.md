# laravel-package-helper
Provide commands to create laravel package files.

## install
```bash
composer require pragnesh/laravel-package-helper --dev
```

## Available Commands
---
Commands:

```bash
./vendor/bin/larapack config:install
```
Description:
will create config/larapack.php file update `package-namespace` variable as per your package namespace

## Provider

```bash
./vendor/bin/larapack make:provider NAME
```

```
Arguments:
  name                  The name of the provider class
```

## Model

```bash
./vendor/bin/larapack make:model NAME
```

```
Arguments:
  name                  The name of the model class

Options:
  -a, --all             Generate a migration, seeder, factory, policy, resource controller, and form request classes for the model
  -c, --controller      Create a new controller for the model
  -f, --factory         Create a new factory for the model
  -m, --migration       Create a new migration file for the model
      --morph-pivot     Indicates if the generated model should be a custom polymorphic intermediate table model
      --policy          Create a new policy for the model
  -s, --seed            Create a new seeder for the model
  -p, --pivot           Indicates if the generated model should be a custom intermediate table model
  -r, --resource        Indicates if the generated controller should be a resource controller
      --api             Indicates if the generated controller should be an API resource controller
  -R, --requests        Create new form request classes and use them in the resource controller
```

## Controller

```bash
./vendor/bin/larapack make:controller NAME
```
```
Arguments:
  name                  The name of the controller class

Options:
      --api             Exclude the create and edit methods from the controller.
  -i, --invokable       Generate a single method, invokable controller class.
  -m, --model[=MODEL]   Generate a resource controller for the given model.
  -r, --resource        Generate a resource controller class.
  -R, --requests        Generate FormRequest classes for store and update.

```