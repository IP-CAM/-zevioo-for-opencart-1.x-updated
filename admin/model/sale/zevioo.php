<?php
class ModelSaleZevioo extends Model {
	function eventCancelOrder($order_id){
		$this->load->model('sale/order');
		$this->zevioo_write_log('eventEditOrder Event fired: #' . $order_id);
		
		$order = $this->model_sale_order->getOrder($order_id);
		if($order['order_status_id'] == $this->config->get('module_zevioo_canceled_status_id')){
			$orderData = array(
									'USR' => $this->config->get('module_zevioo_username'),
									'PSW' => $this->config->get('module_zevioo_password'),
									'OID' => $order['order_id'],
									'CDT' => date('Y-m-d H:i:s')
							);
			$this->zevioo_write_log('Zevioo cancel request: ' . print_r($orderData, true));
			$returnData  = array('http_status'=>'', 'data'=>'');
			
			$service_cancel_order_url = 'https://api.zevioo.com/main.svc/cnlpurchase';
			$returnData = $this->apiPostRequest($service_cancel_order_url, $orderData);
					
			if($returnData['http_status'] == '200'){
				$message = "Success Cancel Order: ". print_r($returnData['data'], true);
			} else {
				$message = "Error Cancel Order: ". print_r($returnData['data'], true);
			}
					
			$this->zevioo_write_log($message);
		}
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