<?php namespace SilvertipSoftware\LaravelTraitPack;

trait HasActiveState {

    public static function bootHasActiveState() {
        $fieldName = trait_config_get( get_class(), 'SilvertipSoftware\LaravelTraitPack\HasActiveState.field', 'active' );
        trait_config_set( get_class(), 'SilvertipSoftware\LaravelTraitPack\ValidatesTrait.rules.'.$fieldName, ['required','in:0,1'] );
    }

    public function isActive() {
        $fieldName = trait_config_get( get_class(), 'SilvertipSoftware\LaravelTraitPack\HasActiveState.field', 'active' );
        return $this->$fieldName == 1;
    }

    public function scopeActive( $query ) {
        $fieldName = trait_config_get( get_class(), 'SilvertipSoftware\LaravelTraitPack\HasActiveState.field', 'active' );
        return $query->where( $fieldName, 1 );
    }
}