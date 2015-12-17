<?php namespace SilvertipSoftware\LaravelTraitPack;

trait ValidatesTrait {

    public $errors = null;

    public function isValid() {
        $class = get_called_class();
        $rules = trait_config_get( get_called_class(), 'SilvertipSoftware\LaravelTraitPack\ValidatesTrait.rules', [] );

        if ( !$this->exists ) {
            $creationRules = trait_config_get( get_called_class(), 'SilvertipSoftware\LaravelTraitPack\ValidatesTrait.creation_rules', [] );
            $rules = array_merge( $rules, $creationRules );
        }

        if ( empty($rules) ) {
            return true;
        }

       $attrs = $this->getAttributes();

        // loop through any mutated attributes and add those that have rules, and aren't there already
        $mutated = $this->getMutatedAttributes();
        foreach( $mutated as $key ) {
            if ( !array_key_exists($key, $attrs) && array_key_exists($key, $rules) ) {
                $attrs[$key] = $this->mutateAttributeForArray($key, null);
            } 
        }

        $validator = \Validator::make( $attrs, $rules );
        $ret = $validator->passes();
        if ( !$ret ) {
            $this->errors = $validator->messages();
        }
        return $ret;
    }

    public function mergeErrors( $bag ) {
        if ( $bag == null ) return;

        if ( $this->errors == null )
            $this->errors = new \Illuminate\Support\MessageBag();
        $this->errors->merge( $bag->getMessages() );
    }

    public static function bootValidatesTrait() {
        static::saving( function($model) {
            return $model->isValid();
        });
    }

}
