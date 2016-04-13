<?php namespace Sanatorium\Variants\Repositories\Variant;

interface VariantRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Variants\Models\Variant
	 */
	public function grid();

	/**
	 * Returns all the variants entries.
	 *
	 * @return \Sanatorium\Variants\Models\Variant
	 */
	public function findAll();

	/**
	 * Returns a variants entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Variants\Models\Variant
	 */
	public function find($id);

	/**
	 * Determines if the given variants is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given variants is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given variants.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a variants entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Variants\Models\Variant
	 */
	public function create(array $data);

	/**
	 * Updates the variants entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Variants\Models\Variant
	 */
	public function update($id, array $data);

	/**
	 * Deletes the variants entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
