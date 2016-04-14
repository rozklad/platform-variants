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
	protected $table = 'shop_variants';

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

}
