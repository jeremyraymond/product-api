<?php

class ProductController extends Controller
{

	public function getProducts()
	{
		// query string parameters
		$product_name = ''; if(isset($_GET['product_name'])) $product_name = $_GET['product_name'];
		$top_price = ''; if(isset($_GET['top_price'])) $top_price = $_GET['top_price'];
		$bot_price = ''; if(isset($_GET['bot_price'])) $bot_price = $_GET['bot_price'];
		$in_stock = ''; if(isset($_GET['in_stock'])) $in_stock = $_GET['in_stock'];
		$manufacturer_guid = ''; if(isset($_GET['manufacturer_guid'])) $manufacturer_guid = $_GET['manufacturer_guid'];
		$per_page = 1000; if(isset($_GET['per_page'])) $per_page = $_GET['per_page'];

		// build the query
		$products = DB::table('products')
			->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.manufacturer_id' )
			->select('product_guid', 'product_name', 'price', 'description', 'manufacturer_guid', 'manufacturer_name', 'amount_in_stock')
			->where(function($products) use ($product_name, $top_price, $bot_price, $in_stock, $manufacturer_guid)
			{
				// conditional WHERE statements based on query string parameters
				if ($product_name) {
					$products->where('product_name', 'like', '%'.$product_name.'%');
				}
				if ($top_price) {
					$products->where('price', '<=', $top_price);
				}
				if ($bot_price) {
					$products->where('price', '>=', $bot_price);
				}
				if ($in_stock) {
					$products->where('amount_in_stock', '>', 0);
				}
				if ($manufacturer_guid) {
					$products->where('manufacturer_guid', '=', $manufacturer_guid);
				}
			})
			->get();
		return json_encode($products);
	}
	public function getSingleProduct($guid)
	{
		$products = DB::table('products')
			->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.manufacturer_id' )
			->select('guid', 'product_name', 'price', 'description', 'manufacturer_name', 'amount_in_stock')
			->where('guid', '=', $guid)
			->get();
		return json_encode($products);
	}
}