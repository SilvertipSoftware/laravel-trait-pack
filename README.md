# laravel-trait-pack

A basic collection of some useful traits for Laravel 5.

## Overview

Most traits are configurable, and have standardized on a way of storing these 
config parameters without polluting the property names within the using class.

Trait config parameters are stored in a `$traitConfigs` static property. This is
an associative array, where keys are defined by the trait. The ones in this pack
use the fully qualified name of the trait, but that is not enforced.

For example,
````php
    $traitConfig = [
        'SilvertipSoftware\LaravelTraitPack\HasActiveState' => [ 'field' => 'flag' ]
    ];
````

## HasActiveState

Adds an `isActive` method and a scope to a Laravel `Model`. The underlying field 
name is configurable, and defaults to `active`.

### Example Config

````php
    $traitConfig = [
        'SilvertipSoftware\LaravelTraitPack\HasActiveState' => [ 'field' => 'flag' ]
    ];
````

## UsesAlternateConnection

Allows configurable connection names per class, or class hierarchies. Particularly 
useful for libraries. The connection name can either be defined directly on the 
class using:
````php
    $traitConfig = [
        'SilvertipSoftware\LaravelTraitPack\UsesAlternateConnection' => [ 'connection' => 'other_db' ]
    ]
````
or within a config file, under the `database.alternateConnections` tag.
````
    'alternateConnections' => [
        'FullyQualifiedClassname' => 'other_db',
        'AnotherClassName' => 'some_other_connection'
    ]
````
Classnames are searched up the hierarchy, so just the base class name can be 
specified in the config file. Defaults to `null`, or the default connection name.

## ValidatesTrait

Adds an `isValid` method to a model, with a configurable set of rules. Rules are 
specifed with the `rules` trait config parameter. Additional rules can be specified
with the `creation_rules` parameter.

### Example
````php
    $traitConfigs = [
        'SilvertipSoftware\LaravelTraitPack\ValidatesTrait' => [
            'rules' => [
                'name' => [ 'required', 'string', 'max:20' ],
                'age' => [ 'required', 'numeric', 'min:18' ]
            ],
            'creation_rules' => [
                'password' => [ 'required', 'string' ],
                'password_confirm' => [ 'required', 'string' ]
            ]
        ]
    ]
````
The `$model->errors` message bag is set with any validation errors.
