<?php namespace Sanatorium\Variants\Handlers\Variant;

use Illuminate\Events\Dispatcher;
use Sanatorium\Variants\Models\Variant;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class VariantEventHandler extends BaseEventHandler implements VariantEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.variants.variant.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.variants.variant.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.variants.variant.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.variants.variant.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.variants.variant.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Variant $variant)
	{
		$this->flushCache($variant);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Variant $variant, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Variant $variant)
	{
		$this->flushCache($variant);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Variant $variant)
	{
		$this->flushCache($variant);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Variants\Models\Variant  $variant
	 * @return void
	 */
	protected function flushCache(Variant $variant)
	{
		$this->app['cache']->forget('sanatorium.variants.variant.all');

		$this->app['cache']->forget('sanatorium.variants.variant.'.$variant->id);
	}

}
