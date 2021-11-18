<?php namespace SilvertipSoftware\LaravelTraitPack;

use Illuminate\Contracts\Validation\Validator;
use \Symfony\Contracts\Translation\TranslatorInterface;

class ExtendedValidator extends Validator {

    public function __construct(TranslatorInterface $translator, $data, $rules, $messages = array())
    {
        parent::__construct($translator, $data, $rules, $messages);
        array_push( $this->implicitRules, 'RequiredIfNot' );
    }
    
    /* 'field': 'exists_in_account:tablename,field' */
    public function validateExistsInAccount( $attribute, $value, $parameters ) {
        $accountIdFld = isset( $parameters[1] ) ? $parameters[1] : 'account_id';
        return $this->validateExists( $attribute, $value, array(
            $parameters[0], 'id', $accountIdFld, $this->data[$accountIdFld]
        ));
    }

    /* 'field' => 'unique_except_for_self:tablename,attrname,id,scope_attrname,...' */
    public function validateUniqueExceptForSelf( $attribute, $value, $parameters ) {
        $extras = array();
        $primaryKey = ( isset($parameters[2]) ? $parameters[2] : 'id');
        if ( isset( $parameters[3]) ) {
            $scopes = array_slice( $parameters, 3 );
            foreach ( $scopes as $scope )
                array_push( $extras, $scope, $this->data[$scope] );
        }
        return $this->validateUnique( $attribute, $value, array_merge( 
            array(
                $parameters[0],
                isset($parameters[1]) ? $parameters[1] : $attribute,
                isset($this->data[$primaryKey]) ? $this->data[$primaryKey] : null,
                isset($this->data[$primaryKey]) ? $primaryKey : null
            ),
            $extras
        ));
    }

    /* 'field' => 'required_if_not:field,value' */
    public function validateRequiredIfNot( $attribute, $value, $parameters ) {
        if ($parameters[1] != array_get($this->data, $parameters[0]))
        {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }
}
