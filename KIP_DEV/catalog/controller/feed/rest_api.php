<?php
/**
 * rest_api.php
 *
 * Custom rest services
 *
 * @author     Makai Lajos
 * @copyright  2014
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://webshop.opencart-api.com/schema_v2.0/
 */

class ControllerFeedRestApi extends Controller {

    private $debugIt = false;
    private static $productFieds = array(
        "model",
        "sku",
        "upc",
        "ean",
        "jan",
        "isbn",
        "mpn",
        "location",
        "quantity",
        "minimum",
        "subtract",
        "stock_status_id",
        "date_available",
        "manufacturer_id",
        "shipping",
        "price",
        "points",
        "weight",
        "weight_class_id",
        "length",
        "width",
        "height",
        "length_class_id",
        "status",
        "tax_class_id",
        "sort_order",
	"image",
	"product_store"
    );

    private static $productFiedsDefaultValue = array(
        "quantity"=>1,
        "minimum"=>1,
        "subtract"=>1,
        "stock_status_id"=>0,
        "shipping"=> 1,
        "manufacturer_id"=> 0,
        "status"=>1,
	"product_store"=>array(0),
        "tax_class_id"=> 0,
        "sort_order" => 1
    );

    private function checkPlugin() {
	$this->config->set('config_error_display', 0);
        $this->response->addHeader('Content-Type: application/json');

        $json = array("success"=>false);

        /*check rest api is enabled*/
        if (!$this->config->get('rest_api_status')) {
            $json["error"] = 'API is disabled. Enable it!';
        }


        $headers = apache_request_headers();

        $key = "";

        if(isset($headers['X-Oc-Merchant-Id'])){
            $key = $headers['X-Oc-Merchant-Id'];
        }else if(isset($headers['X-OC-MERCHANT-ID'])) {
            $key = $headers['X-OC-MERCHANT-ID'];
        }

        /*validate api security key*/
        if ($this->config->get('rest_api_key') && ($key != $this->config->get('rest_api_key'))) {
            $json["error"] = 'Invalid secret key';
        }

	if(isset($json["error"])){			
		echo(json_encode($json));
		exit;
	}
    }

    /*check database modification*/
    public function getchecksum() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){

            $this->load->model('catalog/product');

            $checksum = $this->model_catalog_product->getChecksum();

            $checksumArray = array();

            for ($i = 0; $i<count($checksum);$i++){
                $checksumArray[] = array('table' => $checksum[$i]['Table'], 'checksum' => $checksum[$i]['Checksum']);
            }

            $json = array('success' => true,'data' => $checksumArray);

            $this->sendResponse($json);
        }
    }

    /*
    * PRODUCT FUNCTIONS
    */
    public function products() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get product details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getProduct($this->request->get['id']);
            }else {
                //get products list

                /*check category id parameter*/
                if (isset($this->request->get['category']) && ctype_digit($this->request->get['category'])) {
                    $category_id = $this->request->get['category'];
                } else {
                    $category_id = 0;
                }

                $this->listProducts($category_id, $this->request);
            }
        }else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            //insert product
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (!empty($requestjson)) {
                $this->addProduct($requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }else if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
            //update product
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])
                && !empty($requestjson)) {
                $this->updateProduct($this->request->get['id'], $requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }else if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' ){
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->deleteProduct($this->request->get['id']);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }
        }
    }

    /*
    * Get products list
    */
    public function listProducts($category_id, $request) {

        $json = array('success' => false);

        $this->load->model('catalog/product');

        $parameters = array(
            "limit" => 100,
            "start" => 1,
            'filter_category_id' => $category_id
        );

        /*check limit parameter*/
        if (isset($request->get['limit']) && ctype_digit($request->get['limit'])) {
            $parameters["limit"] = $request->get['limit'];
        }

        /*check page parameter*/
        if (isset($request->get['page']) && ctype_digit($request->get['page'])) {
            $parameters["start"] = $request->get['page'];
        }

        /*check search parameter*/
        if (isset($request->get['search']) && !empty($request->get['search'])) {
            $parameters["filter_name"] = $request->get['search'];
	    $parameters["filter_tag"]  = $request->get['search'];
        }

        /*check sort parameter*/
        if (isset($request->get['sort']) && !empty($request->get['sort'])) {
            $parameters["sort"] = $request->get['sort'];
        }

        /*check order parameter*/
        if (isset($request->get['order']) && !empty($request->get['order'])) {
            $parameters["order"] = $request->get['order'];
        }

        $parameters["start"] = ($parameters["start"] - 1) * $parameters["limit"];

        $products = $this->model_catalog_product->getProductsData($parameters, $this->customer);

        if (count($products) == 0 || empty($products)) {
            $json['success'] = false;
            $json['error'] = "No product found";
        } else {
            $json['success'] = true;
            foreach ($products as $product) {
                $json['data'][] = $this->getProductInfo($product);
            }
        }

        $this->sendResponse($json);
    }

    /*
    * Get product details
    */
    public function getProduct($id) {

        $json = array('success' => true);

        $this->load->model('catalog/product');

        $products = $this->model_catalog_product->getProductsByIds(array($id), $this->customer);
        
        if(!empty($products)) {
            $json["data"] = $this->getProductInfo(reset($products));
        } else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }

    private function getProductInfo($product){

        $this->load->model('tool/image');
        $this->load->model('catalog/category');

        //product image
        if (isset($product['image']) && file_exists(DIR_IMAGE . $product['image'])) {
            $image = $this->model_tool_image->resize($product['image'], 500, 500);
        } else {
            $image = $this->model_tool_image->resize('no_image.jpg', 500, 500);
        }

        //additional images
        $additional_images = $this->model_catalog_product->getProductImages($product['product_id']);

        $images = array();

        foreach ($additional_images as $additional_image) {
            if (isset($additional_image['image']) && file_exists(DIR_IMAGE . $additional_image['image'])) {
                $images[] = $this->model_tool_image->resize($additional_image['image'], 500, 500);
            } else {
                $images[] = $this->model_tool_image->resize('no_image.jpg', 500, 500);
            }
        }

        //special
        if ((float)$product['special']) {
            $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
        } else {
            $special = "";
        }

        //discounts
        $discounts = array();
        $data_discounts = $this->model_catalog_product->getProductDiscounts($product['product_id']);

        foreach ($data_discounts as $discount) {
            $discounts[] = array(
                'quantity' => $discount['quantity'],
                'price' => $this->currency->format($this->tax->calculate($discount['price'], $product['tax_class_id'], $this->config->get('config_tax')))
            );
        }

        //options
        $options = array();

        foreach ($this->model_catalog_product->getProductOptions($product['product_id']) as $option) {
            if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                $option_value_data = array();

                foreach ($option['option_value'] as $option_value) {
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        if ((($this->customer->isLogged() && $this->config->get('config_customer_price')) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                        } else {
                            $price = false;
                        }

                        if (isset($option_value['image']) && file_exists(DIR_IMAGE . $option_value['image'])) {
                            $option_image = $this->model_tool_image->resize($option_value['image'], 100, 100);
                        } else {
                            $option_image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
                        }

                        $option_value_data[] = array(
                            'image'				=> $option_image,
                            'price'				=> $price,
                            'price_prefix'			=> $option_value['price_prefix'],
                            'product_option_value_id'=> $option_value['product_option_value_id'],
                            'option_value_id'		=> $option_value['option_value_id'],
                            'name'				=> $option_value['name'],
                            'quantity'			=> !empty($option_value['quantity']) ? $option_value['quantity'] : 0
                        );
                    }
                }

                $options[] = array(
                    'name'				=> $option['name'],
                    'type'				=> $option['type'],
                    'option_value'		=> $option_value_data,
                    'required'			=> $option['required'],
                    'product_option_id' => $option['product_option_id'],
                    'option_id'			=> $option['option_id']

                );
            } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
                $options[] = array(
                    'name'				=> $option['name'],
                    'type'				=> $option['type'],
                    'option_value'		=> $option['option_value'],
                    'required'			=> $option['required'],
                    'product_option_id' => $option['product_option_id'],
                    'option_id'			=> $option['option_id'],
                );
            }
        }

        $productCategories = array();
        $product_category  = $this->model_catalog_product->getCategories($product['product_id']);

        foreach ($product_category as $prodcat) {
            $category_info = $this->model_catalog_category->getCategory($prodcat['category_id']);
            if ($category_info) {
                $productCategories[] = array(
                    'name' => $category_info['name'],
                    'id' => $category_info['category_id']
                );
            }
        }

	/*reviews*/
	$this->load->model('catalog/review');
	
	$reviews = array();

	$reviews["review_total"] = $this->model_catalog_review->getTotalReviewsByProductId($product['product_id']);

	$reviewList = $this->model_catalog_review->getReviewsByProductId($product['product_id'], 0, 1000);

	foreach ($reviewList as $review) {
		$reviews['reviews'][] = array(
			'author'     => $review['author'],
			'text'       => nl2br($review['text']),
			'rating'     => (int)$review['rating'],
			'date_added' => date($this->language->get('date_format_short'), strtotime($review['date_added']))
		);
	}

        return array(
            'id'				=> $product['product_id'],
            'seo_h1'			=> (!empty($product['seo_h1']) ? $product['seo_h1'] : "") ,
            'name'				=> $product['name'],
            'manufacturer'		=> $product['manufacturer'],
            'sku'				=> (!empty($product['sku']) ? $product['sku'] : "") ,
            'model'				=> $product['model'],
            'image'				=> $image,
            'images'			=> $images,
            'price'				=> $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
            'rating'			=> (int)$product['rating'],
            'description'		=> html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
            'attribute_groups'	=> $this->model_catalog_product->getProductAttributes($product['product_id']),
            'special'			=> $special,
            'discounts'			=> $discounts,
            'options'			=> $options,
            'minimum'			=> $product['minimum'] ? $product['minimum'] : 1,
	    'meta_description'     => $product['meta_description'],
	    'meta_keyword'     => $product['meta_keyword'],
            'tag'              => $product['tag'],
            'upc'              => $product['upc'],
            'ean'              => $product['ean'],
            'jan'              => $product['jan'],
            'isbn'             => $product['isbn'],
            'mpn'              => $product['mpn'],
            'location'         => $product['location'],
            'stock_status'     => $product['stock_status'],
            'manufacturer_id'  => $product['manufacturer_id'],
            'tax_class_id'     => $product['tax_class_id'],
            'date_available'   => $product['date_available'],
            'weight'           => $product['weight'],
            'weight_class_id'  => $product['weight_class_id'],
            'length'           => $product['length'],
            'width'            => $product['width'],
            'height'           => $product['height'],
            'length_class_id'  => $product['length_class_id'],
            'subtract'         => $product['subtract'],
            'sort_order'       => $product['sort_order'],
            'status'           => $product['status'],
            'date_added'       => $product['date_added'],
            'date_modified'    => $product['date_modified'],
            'viewed'           => $product['viewed'],
            'weight_class'     => $product['weight_class'],
            'length_class'     => $product['length_class'],
            'reward'			=> $product['reward'],
            'points'			=> $product['points'],
            'category'			=> $productCategories,
            'quantity'			=> !empty($product['quantity']) ? $product['quantity'] : 0,
	    'reviews' => $reviews
        );
    }

    /*	Update product

    */
    private function updateProduct($id, $data) {

        $json = array('success' => false);

        $this->load->model('catalog/product');

        if (ctype_digit($id)) {
            $valid = $this->model_catalog_product->checkProductExists($id);

            if(!empty($valid)) {
	        $product = $this->model_catalog_product->getProduct($id);
                $this->loadProductSavedData($data, $product);
                if ($this->validateProductForm($data)) {
                    $json['success']     = true;
                    $this->model_catalog_product->editProductById($id, $data);
                } else {
                    $json['success']     = false;
		    $json['error']       = "Validation failed";
                }
            }else {
                $json['success']     = false;
                $json['error']       = "The specified product does not exist.";
            }
        }else {
            $json['success']     = false;
            $json['error']       = "Invalid identifier.";
        }

        $this->sendResponse($json);
    }

    /*
	Insert product
    */
    public function addProduct($data) {

        $json = array('success' => true);

        $this->load->model('catalog/product');

        if ($this->validateProductForm($data, true)) {
            $productId = $this->model_catalog_product->addProduct($data);
            $json['product_id'] = $productId;
        } else {
            $json['success']	= false;
        }

        $this->sendResponse($json);
    }

    /*
    * Delete product
    */
    public function deleteProduct($id) {

        $json['success']     = false;

        $this->load->model('catalog/product');

        if (ctype_digit($id)) {

            $product = $this->model_catalog_product->checkProductExists($id);

            if(!empty($product)) {
                $json['success']     = true;
                $this->model_catalog_product->deleteProduct($id);
            }else {
                $json['success']     = false;
                $json['error']       = "The specified product does not exist.";
            }
        }else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }

    /*
    * BULK PRODUCT FUNCTIONS
    */
    public function bulkproducts() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            //insert products
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (!empty($requestjson) && count($requestjson) > 0) {

                $this->addProducts($requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }else if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
            //update products
            $requestjson = file_get_contents('php://input');
            $requestjson = json_decode($requestjson, true);

            if (!empty($requestjson) && count($requestjson) > 0) {
                $this->updateProducts($requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }
    }

    /*
	Insert products
   */
    public function addProducts($products) {

        $json = array('success' => true);

        $this->load->model('catalog/product');

        foreach($products as $product) {

            if ($this->validateProductForm($product, true)) {
                $this->model_catalog_product->addProduct($product);
            } else {
                $json['success']	= false;
            }
        }

        $this->sendResponse($json);
    }

    /*	Update products

    */
    private function updateProducts($products) {

        $json = array('success' => true);

        $this->load->model('catalog/product');

        foreach($products as $productItem) {

            $id = $productItem['product_id'];

            if (ctype_digit($id)) {

                $valid = $this->model_catalog_product->checkProductExists($id);

                if(!empty($valid)) {
		    $product = $this->model_catalog_product->getProduct($id);

                    $this->loadProductSavedData($productItem, $product);
                    if ($this->validateProductForm($productItem)) {
                        $this->model_catalog_product->editProductById($id, $productItem);
                    } else {
                        $json['success'] 	= false;
                    }

                } else {
                    $json['success']     = false;
                    $json['error']       = "The specified product does not exist.";
                }

            } else {
                $json['success']     = false;
                $json['error']       = "Invalid identifier";
            }
        }

        $this->sendResponse($json);
    }

    private function loadProductSavedData(&$data, $product) {
        foreach(self::$productFieds as $field){
            if(!isset($data[$field])){
                if(isset($product[$field])){
                    $data[$field] = $product[$field];
                } else {
                    $data[$field] = "";
                }
            }
        }
    }


    private function validateProductForm(&$data, $validateSku = false) {

        $error = false;
	
	if($validateSku){
		if ((utf8_strlen($data['sku']) < 2) || (utf8_strlen($data['sku']) > 255)) {
		    $error  = true;
		}
	}

        if (!empty($data['date_available'])) {
            $date_available = date('Y-m-d',strtotime($data['date_available']));
            if($this->validateDate($date_available, 'Y-m-d')) {
                $data['date_available'] = $date_available;
            } else{
                $data['date_available'] = date('Y-m-d');
            }
        }else{
            $data['date_available'] = date('Y-m-d');
        }

	if (!empty($data['length_class_id'])) {
		$data['length_class_id'] = $data['length_class_id'];
	}  else {
		$data['length_class_id'] = $this->config->get('config_length_class_id');
	}

	if (!empty($data['weight_class_id'])) {
		$data['weight_class_id'] = $data['weight_class_id'];
	}  else {
		$data['weight_class_id'] = $this->config->get('config_weight_class_id');
	}

        foreach(self::$productFieds as $field){
            if(!isset($data[$field])){
		//if no default value is available or product update 
		if(!isset(self::$productFiedsDefaultValue[$field]) || !$validateSku){
	                $data[$field] = "";
		} else {
			$data[$field] = self::$productFiedsDefaultValue[$field];
		}
            }
        }

        if (!$error) {
            return true;
        } else {
            return false;
        }
    }

    /*
    * PRODUCT SPECIFIC INFOS
    */
    public function productclasses() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
		$json = array('success' => true);
            	
		$this->load->model('catalog/product');

		$json['data']['stock_statuses'] = $this->model_catalog_product->getStockStatuses();
		$json['data']['length_classes'] = $this->model_catalog_product->getLengthClasses();
		$json['data']['weight_classes'] = $this->model_catalog_product->getWeightClasses();
		$stores_result = $this->model_catalog_product->getStores();

		$stores = array();

		foreach ($stores_result as $result) {
		    $stores[] = array(
		        'store_id'	=> $result['store_id'],
		        'name'      => $result['name']
		    );
		}

		$default_store = array(
		    'store_id'	=> 0,
		    'name'      => $this->config->get('config_name')
		);

		$json['data']['stores'] = array_merge($default_store, $stores);

		$this->sendResponse($json);
        } else{
	      $this->response->setOutput(json_encode(array('success' => false)));
        }
    }
    /*
    * CATEGORY FUNCTIONS
    */
    public function categories() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get category details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getCategory($this->request->get['id']);
            }else {
                //get category list

                /*check parent parameter*/
                if (isset($this->request->get['parent'])) {
                    $parent = $this->request->get['parent'];
                } else {
                    $parent = 0;
                }

                /*check level parameter*/
                if (isset($this->request->get['level'])) {
                    $level = $this->request->get['level'];
                } else {
                    $level = 1;
                }

                $this->listCategories($parent, $level);
            }
        }else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            //insert category data
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (!empty($requestjson)) {
                $this->addCategory($requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }else if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
            //update category data
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])
                && !empty($requestjson)) {
                $this->updateCategory($this->request->get['id'], $requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }else if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' ){
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->deleteCategory($this->request->get['id']);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }

    }


    /*
    * Get categories list
    */
    public function listCategories($parent,$level) {

        $json['success']	= true;

        $this->load->model('catalog/category');

        $data = $this->loadCatTree($parent, $level);

        if(count($data) == 0){
            $json['success'] 	= false;
            $json['error'] 		= "No category found";
        }else {
            $json['data'] = $data;
        }

        $this->sendResponse($json);
    }

    /*
    * Get category details
    */
    public function getCategory($id) {

        $json = array('success' => true);

        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        if (ctype_digit($id)) {
            $category_id = $id;
        } else {
            $category_id = 0;
        }

        $category = $this->model_catalog_category->getCategory($category_id);

        if(isset($category['category_id'])){

            $json['success']	= true;

            if (isset($category['image']) && file_exists(DIR_IMAGE . $category['image'])) {
                $image = $this->model_tool_image->resize($category['image'], 100, 100);
            } else {
                $image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
            }

            $json['data']	= array(
                'id'			=> $category['category_id'],
                'name'			=> $category['name'],
                'description'	=> $category['description'],
                'image'         => $image
            );
        }else {
            $json['success']     = false;
            $json['error']       = "The specified category does not exist.";

        }

        $this->sendResponse($json);
    }

    public function loadCatTree($parent = 0, $level = 1) {

        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        $result = array();

        $categories = $this->model_catalog_category->getCategories($parent);

        if ($categories && $level > 0) {
            $level--;

            foreach ($categories as $category) {

                if (isset($category['image']) && file_exists(DIR_IMAGE . $category['image'])) {
                    $image = $this->model_tool_image->resize($category['image'], 100, 100);
                } else {
                    $image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
                }

                $result[] = array(
                    'category_id'   => $category['category_id'],
                    'parent_id'     => $category['parent_id'],
                    'name'          => $category['name'],
                    'image'         => $image,
                    'categories'    => $this->loadCatTree($category['category_id'], $level)
                );
            }
            return $result;
        }
    }

    /*
    Insert category
    */
    public function addCategory($data) {

        $json = array('success' => true);

        $this->load->model('catalog/category');

        if ($this->validateCategoryForm($data)) {
            $categoryId = $this->model_catalog_category->addCategory($data);
            $json['category_id'] = $categoryId;
        } else {
            $json['success']	= false;
        }

        $this->sendResponse($json);
    }

    /*
    Uppdate category
    */

    public function updateCategory($id, $data) {

        $json = array('success' => false);

        $this->load->model('catalog/category');

        if ($this->validateCategoryForm($data)) {
            if (ctype_digit($id)) {
                $category = $this->model_catalog_category->getCategory($id);

                if(!empty($category)) {
                    $json['success']     = true;
                    $this->model_catalog_category->editCategory($id, $data);
                }else{
                    $json['success']     = false;
                    $json['error']       = "The specified category does not exist.";
                }

            } else {
                $json['success'] 	= false;
            }
        } else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }

    /*
    * Delete category
    */
    public function deleteCategory($id) {

        $json['success']     = false;

        $this->load->model('catalog/category');

        if (ctype_digit($id)) {

            $category = $this->model_catalog_category->getCategory($id);

            if(!empty($category)) {
                $json['success']     = true;
                $this->model_catalog_category->deleteCategory($id);
            }else {
                $json['success']     = false;
                $json['error']       = "The specified product does not exist.";
            }
        }else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }

    protected function validateCategoryForm($data) {

        $error = false;

        foreach ($data['category_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
                $error  = true;
            }
        }
        if (!$error) {
            return true;
        } else {
            return false;
        }
    }

    /*
    * MANUFACTURER FUNCTIONS
    */
    public function manufacturers() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get manufacturer details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getManufacturer($this->request->get['id']);
            }else {
                //get manufacturers list
                $this->listManufacturers();
            }
        }else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            //insert manufacturer
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (!empty($requestjson)) {
                $this->addManufacturer($requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }else if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
            //update manufacturer
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])
                && !empty($requestjson)) {
                $this->updateManufacturer($this->request->get['id'], $requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }else if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' ){
            //delete manufacturer
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->deleteManufacturer($this->request->get['id']);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }
    }

    /*
    * Get manufacturers list
    */
    public function listManufacturers() {

        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');
        $json = array('success' => true);

        $data['start'] = 0;
        $data['limit'] = 1000;

        $results = $this->model_catalog_manufacturer->getManufacturers($data);

        $manufacturers = array();

        foreach ($results as $manufacturer) {
            $manufacturers[] = $this->getManufacturerInfo($manufacturer);
        }

        if(empty($manufacturers)){
            $json['success'] 	= false;
            $json['error'] 	= "No manufacturer found";
        }else {
            $json['data'] 	= $manufacturers;
        }

        $this->sendResponse($json);
    }

    /*
    * Get manufacturer details
    */
    public function getManufacturer($id) {

        $json = array('success' => true);

        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');

        if (ctype_digit($id)) {
            $manufacturer = $this->model_catalog_manufacturer->getManufacturer($id);
            if($manufacturer){
                $json['data'] = $this->getManufacturerInfo($manufacturer);
            } else {
                $json['success']     = false;
                $json['error']       = "The specified manufacturer does not exist.";
            }
        } else {
            $json['success'] 	= false;
        }

        $this->sendResponse($json);
    }

    private function getManufacturerInfo($manufacturer) {
        if (isset($manufacturer['image']) && file_exists(DIR_IMAGE . $manufacturer['image'])) {
            $image = $this->model_tool_image->resize($manufacturer['image'], 100, 100);
        } else {
            $image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        return array(
            'manufacturer_id'=> $manufacturer['manufacturer_id'],
            'name'			=> $manufacturer['name'],
            'image'			=> $image,
            'sort_order'	=> $manufacturer['sort_order']
        );
    }

    /*
        Insert manufacturer
    */

    public function addManufacturer($data) {

        $json = array('success' => true);

        $this->load->model('catalog/manufacturer');

        if ($this->validateManufacturerForm($data)) {
            $manufacturerId = $this->model_catalog_manufacturer->addManufacturer($data);
            $json['manufacturer_id'] = $manufacturerId;
        } else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }

    /*
        Update manufacturer

    */
    public function updateManufacturer($id, $data) {

        $json = array('success' => false);

        $this->load->model('catalog/manufacturer');


        if (ctype_digit($id)) {
            if ($this->validateManufacturerForm($data)) {
                $result = $this->model_catalog_manufacturer->getManufacturer($id);

                if(!empty($result)) {
                    $json['success']     = true;
                    $this->model_catalog_manufacturer->editManufacturer($id, $data);
                }else{
                    $json['success']     = false;
                    $json['error']       = "The specified manufacturer does not exist.";
                }

            } else {
                $json['success'] 	= false;
            }
        } else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }

    /*Delete manufacturer*/
    public function deleteManufacturer($id) {

        $json['success']     = false;

        $this->load->model('catalog/manufacturer');

        if (ctype_digit($id)) {
            if($this->validateManufacturerDelete($id)){

                $result = $this->model_catalog_manufacturer->getManufacturer($id);

                if(!empty($result)) {
                    $json['success']     = true;
                    $this->model_catalog_manufacturer->deleteManufacturer($id);
                }else {
                    $json['success']     = false;
                    $json['error']       = "The specified manufacturer does not exist.";
                }

            }else {
                $json['success']		= false;
                $json['error']			= "Some products belong to this manufacturer";
            }
        }else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }


    protected function validateManufacturerForm($data) {

        $error = false;

        if(isset($data["name"])){
            if ((utf8_strlen($data["name"]) < 2) || (utf8_strlen($data["name"]) > 255)) {
                $error  = true;
            }
        }else{
            $error  = true;
        }

        if(isset($data["sort_order"])){
            if ((utf8_strlen($data["sort_order"]) < 1) || (utf8_strlen($data["sort_order"]) > 255)) {
                $error  = true;
            }
        }else{
            $error  = true;
        }

        if (!$error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateManufacturerDelete($manufacturer_id) {

        $error = false;

        $this->load->model('catalog/product');

        $product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);

        if ($product_total) {
            $error  = true;
        }

        if (!$error) {
            return true;
        } else {
            return false;
        }
    }

    /*
    * ORDER FUNCTIONS
    */
    public function orders() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get order details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getOrder($this->request->get['id']);
            }else {
                //get orders list
                $this->listOrders();
            }
        }else if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
            //update order data
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])
                && !empty($requestjson)) {
                $this->updateOrder($this->request->get['id'], $requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }


        }else if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' ){
            //delete order
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->deleteOrder($this->request->get['id']);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }
        }
    }

    /*
    * List orders
    */
    public function listOrders() {

        $json = array('success' => true);


        $this->load->model('account/order');

        /*check offset parameter*/
        if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
            $offset = $this->request->get['offset'];
        } else {
            $offset 	= 0;
        }

        /*check limit parameter*/
        if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit 	= 10000;
        }

        /*get all orders of user*/
        $results = $this->model_account_order->getAllOrders($offset, $limit);

        $orders = array();

        if(count($results)){
            foreach ($results as $result) {

                $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
                $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

                $orders[] = array(
                    'order_id'		=> $result['order_id'],
                    'name'			=> $result['firstname'] . ' ' . $result['lastname'],
                    'status'		=> $result['status'],
                    'date_added'	=> $result['date_added'],
                    'products'		=> ($product_total + $voucher_total),
                    'total'			=> $result['total'],
                    'currency_code'	=> $result['currency_code'],
                    'currency_value'=> $result['currency_value'],
                );
            }

            if(count($orders) == 0){
                $json['success'] 	= false;
                $json['error'] 		= "No orders found";
            }else {
                $json['data'] 	= $orders;
            }

        }else {
            $json['error'] 		= "No orders found";
            $json['success'] 	= false;
        }

        $this->sendResponse($json);
    }

    /*
    * List orders whith details
    */
    public function listorderswithdetails() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){

            $json = array('success' => true);


            $this->load->model('account/order');

            /*check limit parameter*/
            if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit 	= 100000;
            }

            if (isset($this->request->get['filter_date_added_from'])) {
                $date_added_from = date('Y-m-d H:i:s',strtotime($this->request->get['filter_date_added_from']));
                if($this->validateDate($date_added_from)) {
                    $filter_date_added_from = $date_added_from;
                }
            } else {
                $filter_date_added_from = null;
            }

            if (isset($this->request->get['filter_date_added_on'])) {
                $date_added_on = date('Y-m-d',strtotime($this->request->get['filter_date_added_on']));
                if($this->validateDate($date_added_on, 'Y-m-d')) {
                    $filter_date_added_on = $date_added_on;
                }
            } else {
                $filter_date_added_on = null;
            }


            if (isset($this->request->get['filter_date_added_to'])) {
                $date_added_to = date('Y-m-d H:i:s',strtotime($this->request->get['filter_date_added_to']));
                if($this->validateDate($date_added_to)) {
                    $filter_date_added_to = $date_added_to;
                }
            } else {
                $filter_date_added_to = null;
            }

            if (isset($this->request->get['filter_date_modified_on'])) {
                $date_modified_on = date('Y-m-d',strtotime($this->request->get['filter_date_modified_on']));
                if($this->validateDate($date_modified_on, 'Y-m-d')) {
                    $filter_date_modified_on = $date_modified_on;
                }
            } else {
                $filter_date_modified_on = null;
            }

            if (isset($this->request->get['filter_date_modified_from'])) {
                $date_modified_from = date('Y-m-d H:i:s',strtotime($this->request->get['filter_date_modified_from']));
                if($this->validateDate($date_modified_from)) {
                    $filter_date_modified_from = $date_modified_from;
                }
            } else {
                $filter_date_modified_from = null;
            }

            if (isset($this->request->get['filter_date_modified_to'])) {
                $date_modified_to = date('Y-m-d H:i:s',strtotime($this->request->get['filter_date_modified_to']));
                if($this->validateDate($date_modified_to)) {
                    $filter_date_modified_to = $date_modified_to;
                }
            } else {
                $filter_date_modified_to = null;
            }

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($this->request->get['filter_order_status_id'])) {
                $filter_order_status_id = $this->request->get['filter_order_status_id'];
            } else {
                $filter_order_status_id = null;
            }

            $data = array(
                'filter_date_added_on'      => $filter_date_added_on,
                'filter_date_added_from'    => $filter_date_added_from,
                'filter_date_added_to'      => $filter_date_added_to,
                'filter_date_modified_on'   => $filter_date_modified_on,
                'filter_date_modified_from' => $filter_date_modified_from,
                'filter_date_modified_to'   => $filter_date_modified_to,
                'filter_order_status_id'    => $filter_order_status_id,
                'start'						=> ($page - 1) * $limit,
                'limit'						=> $limit
            );


            $results = $this->model_account_order->getOrdersByFilter($data);
            /*get all orders*/
            //$results = $this->model_account_order->getAllOrders($offset, $limit);

            $orders = array();

            if(count($results)){

                foreach ($results as $result) {

                    $orderData = $this->getOrderDetailsToOrder($result);

                    if (!empty($orderData)) {
                        $orders[] = $orderData;
                    }
                }

                if(count($orders) == 0){
                    $json['success'] 	= false;
                    $json['error'] 		= "No orders found";
                }else {
                    $json['data'] 	= $orders;
                }

            }else {
                $json['error'] 		= "No orders found";
                $json['success'] 	= false;
            }
        }else{
            $json['success'] 	= false;
        }

        $this->sendResponse($json);
    }

    /*Get order details*/
    public function getOrder($order_id) {

        $this->load->model('checkout/order');
        $this->load->model('account/order');

        $json = array('success' => true);

        if (ctype_digit($order_id)) {
            $order_info = $this->model_checkout_order->getOrder($order_id);

            if (!empty($order_info)) {
                $json['success'] 	= true;
                $json['data'] 		= $this->getOrderDetailsToOrder($order_info);

            } else {
                $json['success']     = false;
                $json['error']       = "The specified order does not exist.";

            }
        } else {
            $json['success']     = false;
            $json['error']       = "Invalid order id";

        }

        $this->sendResponse($json);
    }

    /*Get all orders of user */
    public function userorders(){

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){

            $json = array('success' => true);

            $user = null;

            /*check user parameter*/
            if (isset($this->request->get['user']) && $this->request->get['user'] != "" && ctype_digit($this->request->get['user'])) {
                $user = $this->request->get['user'];
            } else {
                $json['success'] 	= false;
            }

            if($json['success'] == true){
                $orderData['orders'] = array();

                $this->load->model('account/order');

                /*get all orders of user*/
                $results = $this->model_account_order->getOrdersByUser($user);

                $orders = array();

                foreach ($results as $result) {

                    $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
                    $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

                    $orders[] = array(
                        'order_id'		=> $result['order_id'],
                        'name'			=> $result['firstname'] . ' ' . $result['lastname'],
                        'status'		=> $result['status'],
                        'date_added'	=> $result['date_added'],
                        'products'		=> ($product_total + $voucher_total),
                        'total'			=> $result['total'],
                        'currency_code'	=> $result['currency_code'],
                        'currency_value'=> $result['currency_value'],
                    );
                }

                if(count($orders) == 0){
                    $json['success'] 	= false;
                    $json['error'] 		= "No orders found";
                }else {
                    $json['data'] 	= $orders;
                }
            }else{
                $json['success'] 	= false;
            }
        }

        $this->sendResponse($json);
    }

    private function getOrderDetailsToOrder($order_info) {

        $this->load->model('catalog/product');

        $orderData = array();

        if (!empty($order_info)) {
            foreach($order_info as $key=>$value){
                $orderData[$key] = $value;
            }

            $orderData['products'] = array();

            $products = $this->model_account_order->getOrderProducts($orderData['order_id']);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_account_order->getOrderOptionsMod($orderData['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $option_data[] = array(
                            'name'  => $option['name'],
                            'value' => $option['value'],
                            'type'  => $option['type'],
			    'product_option_id'  => isset($option['product_option_id']) ? $option['product_option_id'] : "",
			    'product_option_value_id'  => isset($option['product_option_value_id']) ? $option['product_option_value_id'] : "",
                            'option_id' => isset($option['option_id']) ? $option['option_id'] : "",
                            'option_value_id'  => isset($option['option_value_id']) ? $option['option_value_id'] : ""
                        );
                    } else {
                        $option_data[] = array(
                            'name'  => $option['name'],
                            'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
                            'type'  => $option['type']
                        );
                    }
                }

                $origProduct = $this->model_catalog_product->getProduct($product['product_id']);

                $orderData['products'][] = array(
                    'order_product_id' => $product['order_product_id'],
                    'product_id'       => $product['product_id'],
                    'name'    	 	   => $product['name'],
                    'model'    		   => $product['model'],
                    'sku'			   => (!empty($origProduct['sku']) ? $origProduct['sku'] : "") ,
                    'option'   		   => $option_data,
                    'quantity'		   => $product['quantity'],
                    'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }
        }

	$orderData['histories'] = array();

	$histories = $this->model_account_order->getOrderHistoriesRest($orderData['order_id'],0,1000 );

	foreach ($histories as $result) {
		$orderData['histories'][] = array(
			'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
			'status'     => $result['status'],
			'comment'    => nl2br($result['comment']),
			'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
		);
	}

        return $orderData;
    }

    /*
        Update order status

    */
    public function updateOrder($id, $data) {


        $json = array('success' => false);

        $this->load->model('checkout/order');

        if (ctype_digit($id)) {

            if (isset($data['status']) && ctype_digit($data['status'])) {

                $result = $this->model_checkout_order->getOrder($id);
                if(!empty($result)) {
                    $json['success']     = true;
                    $this->model_checkout_order->update($id, $data['status']);
                }else {
                    $json['success']     = false;
                    $json['error']       = "The specified order does not exist.";
                }

            } else {
                $json['success'] 	= false;
            }
        } else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }

    /*Delete order*/
    public function deleteOrder($id) {

        $json['success']     = false;

        $this->load->model('checkout/order');

        if (ctype_digit($id)) {
            $result = $this->model_checkout_order->getOrder($id);

            if(!empty($result)) {
                $json['success']     = true;
                $this->model_checkout_order->deleteOrder($id);
            }else{
                $json['success']     = false;
                $json['error']       = "The specified order does not exist.";
            }

        }else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }
    /*
    * REVIEW FUNCTIONS
    */
    public function reviews() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            //add review
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])
                && !empty($requestjson)) {
                $this->addReview($this->request->get['id'], $requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }
        }else{
		$this->response->setOutput(json_encode(array('success' => false)));
	}
        
    }

    /*add review*/
    public function addReview($id, $post) {
		
        $json['success']     = false;

	$this->load->language('product/product');

	if ((utf8_strlen($post['name']) < 3) || (utf8_strlen($post['name']) > 25)) {
		$json['error'][] = $this->language->get('error_name');
	}

	if ((utf8_strlen($post['text']) < 25) || (utf8_strlen($post['text']) > 1000)) {
		$json['error'][] = $this->language->get('error_text');
	}

	if (empty($post['rating']) || $post['rating'] < 0 || $post['rating'] > 5) {
		$json['error'][] = $this->language->get('error_rating');
	}

	if (!isset($json['error'])) {
		$this->load->model('catalog/review');
		$this->model_catalog_review->addReview($id, $post);
		$json['success'] = "true";
	}
	
        $this->sendResponse($json);
    }
    /*
    * CUSTOMER FUNCTIONS
    */
    public function customers() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get customer details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getCustomer($this->request->get['id']);
            }else {
                //get customers list
                $this->listCustomers();
            }
        }else if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
            //update customer
            $requestjson = file_get_contents('php://input');

            $requestjson = json_decode($requestjson, true);

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])
                && !empty($requestjson)) {
                $this->updateCustomer($this->request->get['id'], $requestjson);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }

        }else if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' ){
            //delete customer
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->deleteCustomer($this->request->get['id']);
            }else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }
        }
    }

    /*
    * Get customers list
    */
    private function listCustomers() {

        $json = array('success' => true);

        $this->load->model('account/customer');

        $results = $this->model_account_customer->getCustomersMod();

        $customers = array();

        foreach ($results as $customer) {
            $customers[] = $this->getCustomerInfo($customer);
        }

        if(count($customers) == 0){
            $json['success'] 	= false;
            $json['error'] 		= "No customers found";
        }else {
            $json['data'] 		= $customers;
        }

        $this->sendResponse($json);
    }

    /*
    * Get customer details
    */
    private function getCustomer($id) {

        $json = array('success' => true);

        $this->load->model('account/customer');

        if (ctype_digit($id)) {
            $customer = $this->model_account_customer->getCustomer($id);
            if(!empty($customer['customer_id'])){
                $json['data'] = $this->getCustomerInfo($customer);
            }else {
                $json['success']     = false;
                $json['error']       = "The specified customer does not exist.";
            }
        } else {
            $json['success'] 	= false;
        }

        $this->sendResponse($json);
    }

    private function getCustomerInfo($customer) {
        return array(
            'store_id'                => $customer['store_id'],
            'customer_id'             => $customer['customer_id'],
            'firstname'               => $customer['firstname'],
            'lastname'                => $customer['lastname'],
            'telephone'               => $customer['telephone'],
            'fax'                     => $customer['fax'],
            'email'                   => $customer['email']
        );
    }

    /*
	Update customer
   */
    private function updateCustomer($id, $data) {

        $json = array('success' => false);

        $this->load->model('account/customer');

        if ($this->validateCustomerForm($data)) {
            if (ctype_digit($id)) {
                $result = $this->model_account_customer->getCustomer($id);
                if(!empty($result)) {
                    $enableModification = true;

                    //if user wanted to change current password, we need to check not in use
                    if($result['email'] != strtolower($data['email'])){
                        $email_query = $this->db->query("SELECT `email` FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(strtolower($data['email'])) . "'");
                        /*check email not used*/
                        if($email_query->num_rows > 0){
                            $enableModification = false;
                            $json['error'] 	= "The email is already used";
                        }
                    }
                    if($enableModification){
                        $json['success']     = true;
                        $this->model_account_customer->editCustomerById($id, $data);
                    }
                }else {
                    $json['success']     = false;
                    $json['error']       = "The specified customer does not exist.";
                }
            }else {
                $json['success'] 	= false;
            }
        } else {
            $json['success']     = false;
        }

        $this->sendResponse($json);
    }

    /*Delete customer*/
    public function deleteCustomer($id) {

        $json['success']     = false;

        $this->load->model('account/customer');

        if (ctype_digit($id)) {
            $result = $this->model_account_customer->getCustomer($id);
            if(!empty($result)) {
                $json['success']     = true;
                $this->model_account_customer->deleteCustomer($id);
            }else{
                $json['success']     = false;
                $json['error']       = "The specified customer does not exist.";
            }
        }else {
            $json['success']     = false;
            $json['error']       = "Invalid id";
        }

        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
            echo '</pre>';
        } else {
            $this->response->setOutput(json_encode($json));
        }
    }

    private function validateCustomerForm($data) {

        $error = false;

        if ((utf8_strlen($data['firstname']) < 2) || (utf8_strlen($data['firstname']) > 255)) {
            $error  = true;
        }

        if ((utf8_strlen($data['lastname']) < 2) || (utf8_strlen($data['lastname']) > 255)) {
            $error  = true;
        }

        if ((utf8_strlen($data['email']) < 2) || (utf8_strlen($data['email']) > 255) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error  = true;
        }

        if (!$error) {
            return true;
        } else {
            return false;
        }
    }

    /*
    * LANGUAGE FUNCTIONS
    */
    public function languages() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get language details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getLanguage($this->request->get['id']);
            }else {
                //get languages list
                $this->listLanguages();
            }
        }
    }

    /*
* ORDER STATUSES FUNCTIONS
*/
    public function order_statuses() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get order statuses list
            $this->listOrderStatuses();
        }
    }

    /*
    * Get order statuses list
    */
    private function listOrderStatuses() {

        $json = array('success' => true);

        $this->load->model('account/order');

        $statuses = $this->model_account_order->getOrderStatuses();

        if(count($statuses) == 0){
            $json['success'] 	= false;
            $json['error'] 		= "No order status found";
        }else {
            $json['data'] 		= $statuses;
        }

        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
        } else {
            $this->response->setOutput(json_encode($json));
        }
    }

    /*
    * Get languages list
    */
    private function listLanguages() {

        $json = array('success' => true);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        if(count($languages) == 0){
            $json['success'] 	= false;
            $json['error'] 		= "No language found";
        }else {
            $json['data'] 		= $languages;
        }

        $this->sendResponse($json);
    }

    /*
    * Get language details
    */
    private function getLanguage($id) {

        $json = array('success' => true);

        $this->load->model('localisation/language');

        if (ctype_digit($id)) {
            $result = $this->model_localisation_language->getLanguage($id);
        } else {
            $json['success']     = false;
            $json['error']       = "Not valid id";
        }

        if(!empty($result)){
            $json['data'] = array(
                'language_id' => $result['language_id'],
                'name'        => $result['name'],
                'code'        => $result['code'],
                'locale'      => $result['locale'],
                'image'       => $result['image'],
                'directory'   => $result['directory'],
                'filename'    => $result['filename'],
                'sort_order'  => $result['sort_order'],
                'status'      => $result['status']
            );
        }else {
            $json['success']     = false;
            $json['error']       = "The specified language does not exist.";
        }

        $this->sendResponse($json);
    }

    /*
    * STORE FUNCTIONS
    */
    public function stores() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get store details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getStore($this->request->get['id']);
            }else {
                //get stores list
                $this->listStores();
            }
        }
    }

    /*
    * Get stores list
    */
    private function listStores() {

        $json = array('success' => true);

        $this->load->model('checkout/order');

        $results = $this->model_checkout_order->getStores();

        $stores = array();

        foreach ($results as $result) {
            $stores[] = array(
                'store_id'	=> $result['store_id'],
                'name'      => $result['name']
            );
        }

        $default_store = array(
            'store_id'	=> 0,
            'name'      => $this->config->get('config_name')
        );

        $data = array_merge($default_store, $stores);

        if(count($data) == 0){
            $json['success'] 	= false;
            $json['error'] 		= "No store found";
        }else {
            $json['data'] 		= $data;
        }

        $this->sendResponse($json);
    }

    /*
    * Get store details
    */
    private function getStore($id) {

        $json = array('success' => true);

        $this->load->model('checkout/order');
        if (ctype_digit($id)) {
            $result = $this->model_checkout_order->getStore($id);
        } else {
            $json['success'] 	= false;
        }

        if(isset($result['store_id'])){
            $json['data'] = array(
                'store_id'	  => $result['store_id'],
                'name'        => $result['name']
            );
        }else {
            $json['success']     = false;
            $json['error']       = "The specified store does not exist.";
        }

        $this->sendResponse($json);
    }


    /*
    * COUNTRY FUNCTIONS
    */
    public function countries() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get country details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getCountry($this->request->get['id']);
            }else {
                $this->listCountries();
            }
        }
    }

    /*
    * Get countries
    */
    private function listCountries() {

        $json = array('success' => true);

        $this->load->model('localisation/country');

        $results = $this->model_localisation_country->getCountries();

        $data = array();

        foreach ($results as $country) {
            $data[] = $this->getCountryInfo($country, false);
        }

        if(count($results) == 0){
            $json['success'] 	= false;
            $json['error'] 		= "No country found";
        }else {
            $json['data'] 		= $data;
        }

        $this->sendResponse($json);
    }

    /*
    * Get country details
    */
    public function getCountry($country_id) {

        $json = array('success' => true);

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($country_id);

        if(!empty($country_info)){
            $json["data"] = $this->getCountryInfo($country_info);
        }else {
            $json['success']     = false;
            $json['error']       = "The specified country does not exist.";
        }

        $this->sendResponse($json);
    }

    private function getCountryInfo($country_info, $addZone = true) {
        $this->load->model('localisation/zone');
        $info = array(
            'country_id'        => $country_info['country_id'],
            'name'              => $country_info['name'],
            'iso_code_2'        => $country_info['iso_code_2'],
            'iso_code_3'        => $country_info['iso_code_3'],
            'address_format'    => $country_info['address_format'],
            'postcode_required' => $country_info['postcode_required'],
            'status'            => $country_info['status']
        );
        if($addZone){
            $info['zone'] = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);
        }

        return $info;
    }

    /*
    * SESSION FUNCTIONS
    */
    public function session() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get session details
            $this->getSessionId();
        }
    }

    /*
    * Get current session id
    */
    public function getSessionId() {

        $json = array('success' => true);

        $json['data'] = array('session' => session_id());

        $this->sendResponse($json);
    }


    /*
    * FEATURED PRODUCTS FUNCTIONS
    */
    public function featured() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            //get featured products
            $limit = 0;

            if (isset($this->request->get['limit']) && ctype_digit($this->request->get['limit']) && $this->request->get['limit'] > 0) {
                $limit = $this->request->get['limit'];
            }

            $this->getFeaturedProducts($limit);
        }
    }

    /*
    * Get featured products
    */
    public function getFeaturedProducts($limit) {

        $json = array('success' => true);

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $products = explode(',', $this->config->get('featured_product'));

        if($limit){
            $products = array_slice($products, 0, (int)$limit);
        }

        foreach ($products as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            if ($product_info) {
                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], 500, 500);
                } else {
                    $image = false;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$product_info['special']) {
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $product_info['rating'];
                } else {
                    $rating = false;
                }

                $json['data'][] = array(
                    'product_id' => $product_info['product_id'],
                    'thumb'   	 => $image,
                    'name'    	 => $product_info['name'],
                    'price'   	 => $price,
                    'special' 	 => $special,
                    'rating'     => $rating
                );
            }
        }

        $this->sendResponse($json);
    }

    /*
    * PRODUCT IMAGE MANAGEMENT FUNCTIONS
    */
    public function productimages() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            //upload and save image
            if (!empty($this->request->get['other']) && $this->request->get['other'] == 1) {
                $this->addProductImage($this->request);
            } else {
                $this->updateProductImage($this->request);
            }
        }
    }

    /*
    * Upload and save product image
    */
    public function addProductImage($request) {

        $json = array('success' => false);

        $this->load->model('catalog/product');

        if (ctype_digit($request->get['id'])) {
            $product = $this->model_catalog_product->getProduct($request->get['id']);
            //check product exists
            if(!empty($product)) {
                if(isset($request->files['file'])){
                    $uploadResult = $this->upload($request->files['file'], "products");
                    if(!isset($uploadResult['error'])){
                        $json['success']     = true;
                        $this->model_catalog_product->addProductImage($request->get['id'], $uploadResult['file_path']);
                    }else{
                        $json['error']    = $uploadResult['error'];
                    }
                } else {
                    $json['error']	= "File is required!";
                }
            }else {
                $json['success']	= false;
                $json['error']      = "The specified product does not exist.";
            }
        } else {
            $json['success']    = false;
        }

        $this->sendResponse($json);
    }

    /*
    * Upload and update product image
    */
    public function updateProductImage($request) {

        $json = array('success' => false);

        $this->load->model('catalog/product');

        if (ctype_digit($request->get['id'])) {
            $product = $this->model_catalog_product->getProduct($request->get['id']);
            //check product exists
            if(!empty($product)) {
                if(isset($request->files['file'])){
                    $uploadResult = $this->upload($request->files['file'], "products");
                    if(!isset($uploadResult['error'])){
                        $json['success']     = true;
                        $this->model_catalog_product->setProductImage($request->get['id'], $uploadResult['file_path']);
                    }else{
                        $json['error']	= $uploadResult['error'];
                    }
                } else {
                    $json['error']	= "File is required!";
                }
            }else {
                $json['success']	= false;
                $json['error']      = "The specified product does not exist.";
            }
        } else {
            $json['success']    = false;
        }

        $this->sendResponse($json);
    }


    /*
    * CATEGORY IMAGE MANAGEMENT FUNCTIONS
    */
    public function categoryimages() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            //upload and save image
            $this->saveCategoryImage($this->request);
        }
    }

    /*
    * Upload and save category image
    */
    public function saveCategoryImage($request) {
        $json = array('success' => false);

        $this->load->model('catalog/category');

        if (ctype_digit($request->get['id'])) {
            $category = $this->model_catalog_category->getCategory($request->get['id']);
            //check category exists
            if(!empty($category)) {
                if(isset($request->files['file'])){
                    $uploadResult = $this->upload($request->files['file'], "categories");
                    if(!isset($uploadResult['error'])){
                        $json['success']     = true;
                        $this->model_catalog_category->setCategoryImage($request->get['id'], $uploadResult['file_path']);
                    }else{
                        $json['error']	= $uploadResult['error'];
                    }
                } else {
                    $json['error']	= "File is required!";
                }
            }else {
                $json['success']	= false;
                $json['error']      = "The specified category does not exist.";
            }
        } else {
            $json['success']    = false;
        }

        $this->sendResponse($json);
    }

    /*
* GET UTC AND LOCAL TIME DIFFERENCE
    * returns offset in seconds
*/
    public function utc_offset() {

        $this->checkPlugin();

        $json = array('success' => false);

        if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
            $serverTimeZone = date_default_timezone_get();
            $timezone = new DateTimeZone($serverTimeZone);
            $now = new DateTime("now", $timezone);
            $offset = $timezone->getOffset($now);

            $json['data'] = array('offset' => $offset);
            $json['success'] = true;
        }

        $this->sendResponse($json);
    }

    /*
    * MANUFACTURER IMAGE MANAGEMENT FUNCTIONS
    */
    public function manufacturerimages() {

        $this->checkPlugin();

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            //upload and save manufacturer image
            $this->saveManufacturerImage($this->request);
        }
    }

    /*
    * Upload and save manufacturer image
    */
    public function saveManufacturerImage($request) {

        $json = array('success' => false);

        $this->load->model('catalog/manufacturer');

        if (ctype_digit($request->get['id'])) {
            $manufacturer = $this->model_catalog_manufacturer->getManufacturer($request->get['id']);
            //check manufacturer exists
            if(!empty($manufacturer)) {
                if(isset($request->files['file'])){
                    $uploadResult = $this->upload($request->files['file'], "manufacturers");
                    if(!isset($uploadResult['error'])){
                        $json['success']     = true;
                        $this->model_catalog_manufacturer->setManufacturerImage($request->get['id'], $uploadResult['file_path']);
                    }else{
                        $json['error']	= $uploadResult['error'];
                    }
                } else {
                    $json['error']	= "File is required!";
                }
            }else {
                $json['success']	= false;
                $json['error']      = "The specified manufacturer does not exist.";
            }
        } else {
            $json['success']    = false;
        }

        $this->sendResponse($json);
    }

    /*
    * Update products quantity
    */
    public function productquantity() {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            //update products
            $requestjson = file_get_contents('php://input');
            $requestjson = json_decode($requestjson, true);

            if (!empty($requestjson) && count($requestjson) > 0) {
                $this->updateProductsQuantity($requestjson);
            } else {
                $this->response->setOutput(json_encode(array('success' => false)));
            }
        } else {
            $json['success'] = false;
            $json['error'] = "Invalid request method, use PUT method.";
            $this->sendResponse($json);
        }
    }

    /*
    * Update products quantity
    */
    private function updateProductsQuantity($products)
    {

        $json = array('success' => true);

        $this->load->model('catalog/product');

        foreach ($products as $productItem) {

            if (isset($productItem['product_id']) && ctype_digit($productItem['product_id'])) {
                //if don't update product option quantity, product quantity must be set
                if(!isset($productItem['product_option'])){
                    if(!isset($productItem['quantity']) || !ctype_digit($productItem['quantity'])) {
                        $json['success'] = false;
                        $json['error'] = "Invalid quantity:".$productItem['quantity'].", product id:".$productItem['product_id'];
                    }
                } else {
                    foreach ($productItem['product_option'][0]['product_option_value'] as $option) {
                        if(!isset($option['quantity']) || !ctype_digit($option['quantity'])) {
                            $json['success'] = false;
                            $json['error'] = "Invalid quantity:".$option['quantity'].", product id:".$productItem['product_id'];
                            break;
                        }
                    }
                }

                if ($json['success']) {
                    $id = $productItem['product_id'];

                    $product = $this->model_catalog_product->checkProductExists($id);

                    if (!empty($product)) {
                        $this->model_catalog_product->editProductQuantity($id, $productItem);
                    } else {
                        $json['success'] = false;
                        $json['error'] = "The specified product does not exist, id: ".$productItem['product_id'];
                    }
                }
            } else {
                $json['success'] = false;
                $json['error'] = "Invalid product id:".$productItem['product_id'];
            }
        }

        $this->sendResponse($json);
    }

    /*
    * Update order status by status name
    */
    public function orderstatus() {

        $this->checkPlugin();
        if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])
            ) {
                $requestjson = file_get_contents('php://input');

                $requestjson = json_decode($requestjson, true);

                $this->updateOrderStatusByName($this->request->get['id'], $requestjson);
            } else {
                $json['success'] = false;
                $json['error'] = "Invalid request, please set order id and order status";

                $this->sendResponse($json);
            }
        }
    }

    /*
     *   Update order status by status name
    */
    public function updateOrderStatusByName($id, $data)
    {

        $json = array('success' => false);

        $this->load->model('checkout/order');

        if (ctype_digit($id)) {
            if (isset($data['status']) && ($data['status']) != "") {

                $status = $this->findStatusByName($data['status']);

                if ($status) {
                    $result = $this->model_checkout_order->getOrder($id);
                    if (!empty($result)) {
                        $json['success'] = true;
                        $this->model_checkout_order->update($id, $status);
                    } else {
                        $json['success'] = false;
                        $json['error'] = "The specified order does not exist.";
                    }
                } else {
                    $json['success'] = false;
                    $json['error'] = "The specified status does not exist.";
                }
            } else {
                $json['success'] = false;
                $json['error'] = "Invalid status id";
            }
        } else {
            $json['success'] = false;
            $json['error'] = "Invalid order id";
        }

        $this->sendResponse($json);

    }

    private function findStatusByName($status_name)
    {
        $this->load->model('catalog/product');

        $status_id = $this->model_catalog_product->getOrderStatusByName($status_name);
        return ((count($status_id) > 0 && $status_id[0]['order_status_id']) ? $status_id[0]['order_status_id'] : false );
    }

	/*
	* ADD ORDER HISTORY
	*/	
	public function orderhistory() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
			$requestjson = file_get_contents('php://input');
	
			$requestjson = json_decode($requestjson, true);           

			if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])
				&& !empty($requestjson)) {
				$this->addOrderHistory($this->request->get['id'], $requestjson);
			}else {
				$this->response->setOutput(json_encode(array('success' => false)));
			}	
		}
    	}

	private function addOrderHistory($id, $data) {
		
	        $json = array('success' => true);       
              
		$this->load->model('account/order');
	   
		$this->model_account_order->addOrderHistoryRest($id, $data);

		$this->response->setOutput(json_encode($json));
	}

    //Image upload
    public function upload($uploadedFile, $subdirectory) {
        $this->language->load('product/product');

        $result = array();

        if (!empty($uploadedFile['name'])) {
            $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($uploadedFile['name'], ENT_QUOTES, 'UTF-8')));

            if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
                $result['error'] = $this->language->get('error_filename');
            }

            // Allowed file extension types
            $allowed = array();

            $filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));

            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }

            if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
                $result['error'] = $this->language->get('error_filetype');
            }

            // Allowed file mime types
            $allowed = array();

            $filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));

            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }

            if (!in_array($uploadedFile['type'], $allowed)) {
                $result['error'] = $this->language->get('error_filetype');
            }

            if ($uploadedFile['error'] != UPLOAD_ERR_OK) {
                $result['error'] = $this->language->get('error_upload_' . $uploadedFile['error']);
            }
        } else {
            $result['error'] = $this->language->get('error_upload');
        }

        if (!$result && is_uploaded_file($uploadedFile['tmp_name']) && file_exists($uploadedFile['tmp_name'])) {
            $file = basename($filename) . '.' . md5(mt_rand());

            // Hide the uploaded file name so people can not link to it directly.
            $result['file'] = $this->encryption->encrypt($file);

            $result['file_path'] = "data/".$subdirectory."/".$filename;
            if($this->rmkdir(DIR_IMAGE."data/".$subdirectory)){
                move_uploaded_file($uploadedFile['tmp_name'], DIR_IMAGE .$result['file_path']);
            }else{
                $result['error'] = "Could not create directory or directory is not writeable: ".DIR_IMAGE ."data/".$subdirectory;
            }
            $result['success'] = $this->language->get('text_upload');
        }
        return $result;

    }

    private function sendResponse($json)
    {
        if ($this->debugIt) {
            echo '<pre>';
            print_r($json);
            echo '</pre>';
        } else {
            $this->response->setOutput(json_encode($json));
        }
    }
    /*
     * Makes directory and returns BOOL(TRUE) if exists OR made.
     */
    function rmkdir($path, $mode = 0777) {

        if (!file_exists($path)) {
            $path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
            $e = explode("/", ltrim($path, "/"));
            if(substr($path, 0, 1) == "/") {
                $e[0] = "/".$e[0];
            }
            $c = count($e);
            $cp = $e[0];
            for($i = 1; $i < $c; $i++) {
                if(!is_dir($cp) && !@mkdir($cp, $mode)) {
                    return false;
                }
                $cp .= "/".$e[$i];
            }
            return @mkdir($path, $mode);
        }

        if (is_writable($path)) {
            return true;
        }else {
            return false;
        }
    }

    //date format validator
    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

}

if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';

        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);

                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }

                    $arh_key = implode('-', $rx_matches);
                }

                $arh[$arh_key] = $val;
            }
        }

        return( $arh );
    }
}
