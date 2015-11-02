<?php namespace SilvertipSoftware\LaravelTraitPack;

use Illuminate\Support\Str;


trait HasDynamicAttributes {
    use \SilvertipSoftware\DynamicMethods\BindingDynamicMethodBehaviour;

    public static function cacheMutatedAttributes($class)
    {
        $mutatedAttributes = [];

        foreach ( array_merge(get_class_methods($class), 
            array_keys(static::$_dynamicMethods[get_called_class()])) as $method) {
            if (strpos($method, 'Attribute') !== false &&
                        preg_match('/^get(.+)Attribute$/', $method, $matches)) {
                if (static::$snakeAttributes) {
                    $matches[1] = Str::snake($matches[1]);
                }

                $mutatedAttributes[] = lcfirst($matches[1]);
            }
        }

        static::$mutatorCache[$class] = $mutatedAttributes;
    }

    public function hasSetMutator($key)
    {
        $methodName = 'set'.Str::studly($key).'Attribute';
        return method_exists($this, $methodName) || static::hasDynamicMethod( $methodName );
    }

    public function hasGetMutator($key)
    {
        $methodName = 'get'.Str::studly($key).'Attribute';
        return method_exists($this, $methodName) || static::hasDynamicMethod($methodName);
    }

    public static function addDynamicAttribute( $key, \Closure $getter, \Closure $setter ) {
        $getterName = 'get'.Str::studly($key).'Attribute';
        $setterName = 'set'.Str::studly($key).'Attribute';
        if ( $getter != null)
            static::addDynamicMethod( $getterName, $getter );
        if ( $setter != null)
            static::addDynamicMethod( $setterName, $setter );
    }
}