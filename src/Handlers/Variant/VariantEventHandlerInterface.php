<?php namespace Sanatorium\Variants\Handlers\Variant;

use Sanatorium\Variants\Models\Variant;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface VariantEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a variant is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a variant is created.
	 *
	 * @param  \Sanatorium\Variants\Models\Variant  $variant
	 * @return mixed
	 */
	public function created(Variant $variant);

	/**
	 * When a variant is being updated.
	 *
	 * @param  \Sanatorium\Variants\Models\Variant  $variant
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Variant $variant, array $data);

	/**
	 * When a variant is updated.
	 *
	 * @param  \Sanatorium\Variants\Models\Variant  $variant
	 * @return mixed
	 */
	public function updated(Variant $variant);

	/**
	 * When a variant is deleted.
	 *
	 * @param  \Sanatorium\Variants\Models\Variant  $variant
	 * @return mixed
	 */
	public function deleted(Variant $variant);

}
