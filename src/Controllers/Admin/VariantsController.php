<?php namespace Sanatorium\Variants\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Variants\Repositories\Variant\VariantRepositoryInterface;

class VariantsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Variants repository.
	 *
	 * @var \Sanatorium\Variants\Repositories\Variant\VariantRepositoryInterface
	 */
	protected $variants;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Variants\Repositories\Variant\VariantRepositoryInterface  $variants
	 * @return void
	 */
	public function __construct(VariantRepositoryInterface $variants)
	{
		parent::__construct();

		$this->variants = $variants;
	}

	/**
	 * Display a listing of variant.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/variants::variants.index');
	}

	/**
	 * Datasource for the variant Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->variants->grid();

		$columns = [
			'id',
			'slug',
			'code',
			'ean',
			'weight',
			'stock',
			'parent_id',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.variants.variants.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new variant.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new variant.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating variant.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating variant.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified variant.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->variants->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/variants::variants/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.variants.variants.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->variants->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a variant identifier?
		if (isset($id))
		{
			if ( ! $variant = $this->variants->find($id))
			{
				$this->alerts->error(trans('sanatorium/variants::variants/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.variants.variants.all');
			}
		}
		else
		{
			$variant = $this->variants->createModel();
		}

		// Show the page
		return view('sanatorium/variants::variants.form', compact('mode', 'variant'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the variant
		list($messages) = $this->variants->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/variants::variants/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.variants.variants.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

	public static function productVariantAttributes($product)
    {
        if ( !is_object($product) )
            return [];

        if ( !method_exists($product, 'variants') )
            return [];

        $result = [];

        foreach( $product->variants()->get() as $variant )
        {
            $result = array_merge($result, array_keys($variant->variantAttributes()));
        }

        return array_unique($result);
    }

    public static function getVariants($product)
    {
        if ( !is_object($product) )
            return [];

        if ( !method_exists($product, 'variants') )
            return [];

        $result = [];

        foreach( $product->variants()->get() as $variant )
        {
            $result[] = [
                'id' => $variant->id,
                'attributes' => $variant->variantAttributes(),
                'price' => $variant->getPrice('plain', 1, null, false, false),
                'price_vat' => $variant->getPrice('vat', 1, null, false, false),
                'ean' => ($variant->ean == '' ? $product->ean : $variant->ean),
                'code' => ($variant->code == '' ? $product->code : $variant->code),
                'stock' => $variant->stock,
                'weight' => $variant->weight
            ];
        }

        return $result;
    }

    public function product()
    {
        $settings = json_decode( request()->get('settings'), true );
        $product_id = request()->get('product');

        $product = app('sanatorium.shop.product')->find($product_id);

        if ( !is_object($product) )
            return response('Fail');

        $variants = [];
        $ids = [];

        foreach( $settings as $variant )
        {
            if ( isset($variant['id']) )
            {
                $variants[] = app('sanatorium.variants.variant')->find($variant['id']);
                $ids[] = $variant['id'];
            }
            if ( isset($variant['draft']) )
            {
                list($messages, $variant) = app('sanatorium.variants.variant')->create($variant['attributes']);
                $variants[] = $variant;
                $ids[] = $variant->id;
            }
        }

        $product->variants()->saveMany($variants);

        app('sanatorium.variants.variant')->whereNotIn('id', $ids)->delete();

        return self::getVariants($product);

    }

}
