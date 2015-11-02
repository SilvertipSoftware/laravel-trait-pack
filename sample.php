<?php

include( __DIR__.'/vendor/autoload.php');

class Another extends\Illuminate\Database\Eloquent\Model {
    public $attributes = [
        'name' => 'John Smith'
    ];
}
class Sample extends \Illuminate\Database\Eloquent\Model {
    use \SilvertipSoftware\LaravelTraitPack\HasDynamicAttributes;

    protected $metaSample;

    public function getTraditionalAttribute($value) {
        return "ABC";
    }
}

Sample::addDynamicAttribute( 'sample', function() {
    // simple retrieval for sample. real world attrs would do much more
    return isset($this->metaSample) ? $this->metaSample : null;
}, function($value) {
    // simple setting for sample. real world attrs would do much more
    $this->metaSample = $value . '_dynamic';
});
Sample::addDynamicMethod( 'tags', function() {
    return $this->belongsTo( 'Another' );
});

$obj = new Sample();
$obj->name = "Bob";
$obj->sample = '1234';
$obj->setRelation( 'tags', new \Illuminate\Database\Eloquent\Collection([ new Another() ]) );
echo $obj->sample . "\n";
echo $obj->toJSON() . "\n";

echo implode(', ', $obj->getMutatedAttributes()) . "\n";
