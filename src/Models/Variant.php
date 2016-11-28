<?php namespace Sanatorium\Variants\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;
use Sanatorium\Pricing\Traits\PriceableTrait;

class Variant extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait, PriceableTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'variants';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
	];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/variants.variant';

	public function variant()
	{
		return $this->belongsTo('Sanatorium\Shop\Models\Product', 'parent_id');
	}

	public function variantAttributes($object = false)
    {
        $attributes = $this->attributesToArray();
        $result = [];

        foreach( $attributes as $attribute => $value )
        {
            $attributeObj = app('platform.attributes')->whereSlug($attribute)->whereNamespace(self::$entityNamespace)->first();

            if ( is_object($attributeObj) )
            {
                $result[$attribute] = $value;
            }

        }

        return $result;
    }

}
