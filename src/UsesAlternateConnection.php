<?php namespace SilvertipSoftware\LaravelTraitPack;

trait UsesAlternateConnection {
    
    public static function resolveConnection($connection = null)
    {
        return static::$resolver->connection( trait_config_get( get_class(), 'SilvertipSoftware\LaravelTraitPack\UsesAlternateConnection.connection' ) );
    }

    public static function bootUsesAlternateConnection() {
        $clz = get_class();
        $conn = trait_config_get( $clz, 'SilvertipSoftware\LaravelTraitPack\UsesAlternateConnection.connection' );
        if ( $conn == null ) {
            foreach( array_merge( [$clz => $clz], class_parents($clz) ) as $clz2 ) {
                $conn = \config( 'database.alternateConnections.' . $clz2 );
                if ( $conn != null )
                    break;
            }
        }
        trait_config_set( $clz, 'SilvertipSoftware\LaravelTraitPack\UsesAlternateConnection.connection', $conn );
    }
}
