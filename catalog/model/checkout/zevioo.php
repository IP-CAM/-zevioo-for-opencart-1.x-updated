<?php
class ModelCheckoutZevioo extends Model {
	function sendToZevioo($order_id){
			$this->zevioo_write_log('eventAddOrder Event fired: #' . $order_id);
			
			$this->load->model('checkout/order');
			$this->load->model('account/order');
			$this->load->model('tool/image');
			$this->load->model('catalog/product');

			$order = $this->model_checkout_order->getOrder($order_id);
			$this->zevioo_write_log('Order info: ' . print_r($order, true));
			
			$first_name = $order['payment_firstname'];
        	$last_name 	= substr($order['payment_lastname'],0,1);
        	$email 		= $order['email'];
			
			$products = $this->model_account_order->getOrderProducts($order_id);
			$this->zevioo_write_log('Products: ' . print_r($products, true));
			foreach ($products as $product)
			{
				$product_info = $this->model_catalog_product->getProduct($product['product_id']);
				
				$image = '';
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
				}

				$EAN = '';
				if($product_info['ean'] != ''){
					$EAN = $product_info['ean'];
				} else if($product_info['upc'] != ''){
					$EAN = $product_info['upc'];
				} else if($product_info['sku'] != ''){
					$EAN = $product_info['sku'];
				} else if($product_info['model'] != ''){
					$EAN = $product_info['model'];
				} else {
					$EAN = $product_info['product_id'];
				}
				$product_item = array(
					'CD' => $product_info['product_id'],
					'EAN' => $EAN,
					'NM' => $product['name'],
					'IMG' => $image,
					'PRC' => $product['price'],
					'QTY' => $product['quantity']
				);
	
				$products_array[] = $product_item;
			}
			
			$orderData = array(
				'USR' => $this->config->get('module_zevioo_username'),
				'PSW' => $this->config->get('module_zevioo_password'),
				'OID' => $order['order_id'],
				'PDT' => date('Y-m-d H:i:s'),
				'DDT' => '',
				'EML' => $email,
				'FN' => $first_name,
				'LN' => $last_name,
				'ITEMS' => $products_array
			);
			
			$this->zevioo_write_log('Zevioo new order request: ' . print_r($orderData, true));
			$returnData  = array('http_status'=>'', 'data'=>'');
			
			$service_new_order_url = 'https://api.zevioo.com/main.svc/custpurchase';
			$returnData = $this->apiPostRequest($service_new_order_url, $orderData);
			
			if($returnData['http_status'] == '200'){
				$message = "Success Send New Order: ". print_r($returnData['data'], true);
			} else {
				$message = "Error Send New Order: ". print_r($returnData['data'], true);
			}
			
			$this->zevioo_write_log($message);
	}

	function apiPostRequest($url, $postData){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
		$data = json_decode($data);
		return array('data'=>$data, 'http_status'=>$http_status);
    }
	function zevioo_write_log($message){
		$fp = fopen(DIR_LOGS . 'zevioo.txt', 'a');
		fwrite($fp, $message);
		fclose($fp);
	}
}
?>