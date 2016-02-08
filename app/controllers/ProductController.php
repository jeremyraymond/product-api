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
		$last_seen = ''; if(isset($_GET['last_seen'])) $last_seen = $_GET['last_seen']; // last viewed product ID for pagination
		$per_page = 1000; if(isset($_GET['per_page'])) $per_page = $_GET['per_page']; // number of results to return
		$order_by = 'product_guid'; if(isset($_GET['order_by'])) $order_by = $_GET['order_by'];
		$order = 'asc'; // asc by default, only a value of desc from $_GET['order'] will change it
		if(isset($_GET['order'])) {
			if($_GET['order'] == 'desc') {
				$order = 'desc';
			}
		}

		// build the query
		$products = DB::table('products')
			->join('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.manufacturer_id' )
			->select('product_id', 'product_guid', 'product_name', 'price', 'description', 'manufacturer_guid', 'manufacturer_name', 'amount_in_stock')
			->where(function($products) use ($product_name, $top_price, $bot_price, $in_stock, $manufacturer_guid, $last_seen)
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
				if ($last_seen) {
					$products->where('product_id', '>' , $last_seen);
				}
			})
			->take($per_page)
			->orderBy($order_by, $order)
			->get();
		return $products;
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