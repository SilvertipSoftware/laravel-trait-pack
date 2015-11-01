<?php

if ( !function_exists('trait_config_get') ) {
    function trait_config_get( $clz, $key, $default = null ) {
        if ( !property_exists( $clz, 'traitConfigs') )
            return $default;

        return array_get($clz::$traitConfigs, $key, $default );
    }
}

if ( !function_exists('trait_config_set') ) {
    function trait_config_set( $clz, $key, $value ) {
        if ( !property_exists( $clz, 'traitConfigs') )
            $clz::$traitConfigs = [];

        array_set($clz::$traitConfigs, $key, $value );
    }
}
