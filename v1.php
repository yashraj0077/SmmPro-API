<?php
  
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  /*
   *  SmmPro.io API v.1
   *  https://www.smmpro.io
  */
  
  class SmmProApi
  {

    private static $api_endpoint = "http://dev.smmpro.io/api/v1";
    private static $api_key = "a6c756-8f0308-b8af39-72d22b-68d4ad"; // Replace by Your API key

    public function newOrder($data){
      $post = array_merge(
        array(
          'api_key' => self::$api_key,
          'action' => 'neworder'
        ), $data
      );
      return json_decode($this->call($post));
    }

    public function getOrderStatus($order_id) {
      return json_decode($this->call(array(
        'api_key' => self::$api_key,
        'action' => 'status',
        'id' => $order_id
      )));
    }

    public function getServices() {
      return json_decode($this->call(array(
        'api_key' => self::$api_key,
        'action' => 'services',
      )));
    }

    public function getBalance() {
      return json_decode($this->call(array(
        'api_key' => self::$api_key,
        'action' => 'balance',
      )));
    }

    private function call($post) {
      $_post = Array();
      if (is_array($post)) {
        foreach ($post as $name => $value) {
          $_post[] = $name.'='.urlencode($value);
        }
      }

      $ch = curl_init(self::$api_endpoint);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      if (is_array($post)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
      }
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
      $result = curl_exec($ch);
      if (curl_errno($ch) != 0 && empty($result)) {
        $result = false;
      }
      curl_close($ch);
      return $result;
    }
  }


  /*
   *  Examples
  */

  $api = new SmmProApi();

  /*
   *  1. Get user balance.
  */

  $balance = $api->getBalance();

  /*
   *  2. Get all SmmPro.io services on json object.
  */

  $services = $api->getServices();

  /*
   *  3. Get order status.
   *  stdClass Object ( [id] => 3191 [source] => instagram [type] => likes [link] => https://www.instagram.com/p/BX561cTAKvj/?taken-by=odil_alison [target] => BX561cTAKvj [cost] => 0.066816 [status] => Done [before] => 699 [requested] => 232 )
  */

  //$order_status = $api->getOrderStatus(3191);

  /*
  $new_order = $api->newOrder(
    array(
    'service_id' => 92,
    'link' => 'http://www.instagram.com/test',
    'quantity' => 100
    )
  );*/


  print_r($balance);
  //print_r($services);
  //print_r($order_status);
  //print_r($new_order);

?>