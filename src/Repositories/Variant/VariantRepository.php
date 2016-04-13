<?php namespace Sanatorium\Variants\Repositories\Variant;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class VariantRepository implements VariantRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Variants\Handlers\Variant\VariantDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent variants model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.variants.variant.handler.data'];

		$this->setValidator($app['sanatorium.variants.variant.validator']);

		$this->setModel(get_class($app['Sanatorium\Variants\Models\Variant']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.variants.variant.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.variants.variant.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new variant
		$variant = $this->createModel();

		// Fire the 'sanatorium.variants.variant.creating' event
		if ($this->fireEvent('sanatorium.variants.variant.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the variant
			$variant->fill($data)->save();

			// Fire the 'sanatorium.variants.variant.created' event
			$this->fireEvent('sanatorium.variants.variant.created', [ $variant ]);
		}

		return [ $messages, $variant ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the variant object
		$variant = $this->find($id);

		// Fire the 'sanatorium.variants.variant.updating' event
		if ($this->fireEvent('sanatorium.variants.variant.updating', [ $variant, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($variant, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the variant
			$variant->fill($data)->save();

			// Fire the 'sanatorium.variants.variant.updated' event
			$this->fireEvent('sanatorium.variants.variant.updated', [ $variant ]);
		}

		return [ $messages, $variant ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the variant exists
		if ($variant = $this->find($id))
		{
			// Fire the 'sanatorium.variants.variant.deleted' event
			$this->fireEvent('sanatorium.variants.variant.deleted', [ $variant ]);

			// Delete the variant entry
			$variant->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
