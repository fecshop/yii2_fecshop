<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
/**
 * Payment services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Shipping extends Service
{
	
	# 得到运费方法。
	public static function getShippingMethod($method=''){
		$allmethod = CConfig::param("shipping_method");
		if($method){
			return $allmethod[$method];
		}else{
			return $allmethod;
		}
	}
	
	# 得到默认的运费方法。
	public static function getDefaultShipping(){
		$allmethod = self::getShippingMethod();
		foreach($allmethod as $k=>$method){
			return $k;
		}
	}
	
	# 在下单页面，得到订单的运费html
	public static function getShippingHtml($weight,$shipping_method,$country){
		//echo "$weight,$shipping_method,$country";exit;
		$shippingName = '';
		//$now_shipping = $this->_shipping_method;
		//$this->_address_country
		$now_shipping = $shipping_method;
		if($now_shipping){
			$shippingName = $now_shipping;
		}
		if(!$shippingName){
			$shippingName = self::getDefaultShipping();
		}
		
		$allshipping = self::getShippingMethod();
		//$weight = $this->quote['total_weight'];
		$sr = '';
		$shipping_i = 1;
		foreach($allshipping as $method=>$shipping){
			$label = $shipping['label'];
			$name = $shipping['name'];
			# 得到运费的金额
			$cost = self::getShippingCostWithSymbols($method,$weight,$country);
			$currentCurrencyCost = $cost['currentCurrencyCost'];
			//echo $method."#".$shippingName."<br/>";
			if($shippingName == $method){
				$check = ' checked="checked" ';
			}else{
				$check = '';
			}
			//echo $check;
			$sr .= '<div class="shippingmethods">
							<dd class="flatrate">'.Translate::__($label).'</dd>
							<dt>
								<input data-role="none" '.$check.' type="radio" id="s_method_flatrate_flatrate'.$shipping_i.'" value="'.$method.'" class="validate-one-required-by-name" name="shipping_method">
								<label for="s_method_flatrate_flatrate'.$shipping_i.'">'.Translate::__($name).'
									<strong>                 
										<span class="price">'.$currentCurrencyCost.'</span>
									</strong>
								</label>
							</dt>
						</div>';
			$shipping_i++;
		}
		return $sr;
	}
	
	
	
	
	
	public static function getShippingByTableCsv($method){
		$commonDir = Yii::getAlias('@common');
		$csv = $commonDir."/config/shipping/".$method.".csv";
		$fp = fopen($csv, "r"); 
		$shippingArr = [];
		$i = 0;
		while(!feof($fp)) 
		{ 	
			if($i){
				$content 		= fgets($fp); 
				$arr 			= explode(",",$content);
				$country 		= $arr[0];
				$Region 		= $arr[1];
				$Weight 		= $arr[3];
				$ShippingPrice 	= $arr[4];
				$shippingArr[$country][$Region][] = [$Weight,$ShippingPrice];
			}
			$i++;
		} 
		fclose($fp); 
		return $shippingArr;
	}
	
	# 通过方法，重量，国家，省，得到美元状态的运费金额
	public static function getShippingCostByCsvWeight($method,$weight,$country,$Region='*'){
		$shippingArr = self::getShippingByTableCsv($method);
		$priceData = [];
		if(isset($shippingArr[$country][$Region])){
			$priceData = $shippingArr[$country][$Region];
		}else{
			$priceData = $shippingArr['*']['*'];
		}
		
		$prev_weight = 0;
		$prev_price = 0;
		$last_price = 0;
		foreach($priceData as $data){
			$csv_weight = (float)$data[0];
			$csv_price  = (float)$data[1];
			if($weight>=$csv_weight){
				$prev_weight 	= $csv_weight;
				$prev_price		= $csv_price;
				continue;
			}else{
				$last_price = $prev_price;
				break;
			}
		}
		if(!$last_price){
			$last_price = $csv_price;
		}
		//return floor($last_price*$weight*100)/100;
		return $last_price;
	}
	
	# 得到运费的金额。
	# 返回为一个数组 默认货币状态下的货币  当前货币状态下的货币。
	public static function getShippingCostWithSymbols($method,$weight,$country =''){
		
		$allmethod = self::getShippingMethod();
		$m = $allmethod[$method];
		
		if(!empty($m) && is_array($m)){
			$cost = $m['cost'];
			# csv方式
			if($cost === 'csv'){
				#通过 运费方式，重量，国家，得到美元的运费 
				$usdCost = self::getShippingCostByCsvWeight($method,$weight,$country);
				//$usdCost = CCurrency::getPertyData($usdCost);
				$currentCost = CCurrency::getCurrentPertyPrice($usdCost);
				return [
					'defaultCost'=>CCurrency::getDefaultSymbols().number_format($usdCost,2) ,
					'currentCurrencyCost'=>CCurrency::getCurrentSymbols().number_format($currentCost,2) ,
				];
			# $cost = 0 代表为free shipping方式
			}else{
				return [
					'defaultCost'=>CCurrency::getDefaultSymbols().number_format(0,2) ,
					'currentCurrencyCost'=>CCurrency::getDefaultSymbols().number_format(0,2) ,
				];
			}
		}
	}
	
	
	
	
	# 通过方法和重量得到运费 
	# 返回值为数组，  defaultCost是默认货币的运费值。 currentCurrencyCost是当前货币状态的运费值 
	public static function getShippingCost($method,$weight,$country =''){
		$allmethod = self::getShippingMethod();
		$m = $allmethod[$method];
		
		if(!empty($m) && is_array($m)){
			$cost = $m['cost'];
			# csv方式
			if($cost === 'csv'){
				#通过 运费方式，重量，国家，得到美元的运费 
				$usdCost = self::getShippingCostByCsvWeight($method,$weight,$country);
				//$usdCost = CCurrency::getPertyData($usdCost);
				$currentCost = CCurrency::getCurrentPertyPrice($usdCost);
				return [
					'defaultCost'=>$usdCost ,
					'currentCurrencyCost'=>$currentCost,
				];
			}else{
				return [
					'defaultCost'=>0 ,
					'currentCurrencyCost'=>0,
				];
			}
		}
	}
	
	
	
	
	# 得到 shipping Lable
	public static function getShippingLabelByMethod($shipping_method){
		$s = self::getShippingMethod($shipping_method);
		return $s['label'];
	}



	
}