<?php

class ProductController extends Controller
{
	/**
	 * Show the profile for the given user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getAll()
	{
		$products = DB::table('products')
			->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.manufacturer_id' )
			->select('product_name', 'price', 'description', 'manufacturer_name')
			->get();
		return json_encode($products);
	}
}