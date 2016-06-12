<?php namespace Sanatorium\Variants\Providers;

use Cartalyst\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class VariantServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Variants\Models\Variant']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.variants.variant.handler.event');

		// Register variant as product
        // AliasLoader::getInstance()->alias('Product', 'Sanatorium\Variants\Models\Variant');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.variants.variant', 'Sanatorium\Variants\Repositories\Variant\VariantRepository');

		// Register the data handler
		$this->bindIf('sanatorium.variants.variant.handler.data', 'Sanatorium\Variants\Handlers\Variant\VariantDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.variants.variant.handler.event', 'Sanatorium\Variants\Handlers\Variant\VariantEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.variants.variant.validator', 'Sanatorium\Variants\Validator\Variant\VariantValidator');
		
	}

}
