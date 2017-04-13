<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\helper;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\services\Service;
use fecshop\models\mongodb\FecshopServiceLog;
/**
 * Country services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Country extends Service
{
	
	public $default_country;
	
	public function getDefaultCountry(){
		if(!$this->default_country){
			$this->default_country = 'US';
		}
		return $this->default_country;
	}
	
	
	public  function getStateOptionsByContryCode($CountryCode,$selected=''){
		if(!$CountryCode){
			$CountryCode = $this->getDefaultCountry();
		}
		$stateArr = $this->getStateByContryCode($CountryCode);
		$str = '';
		if(is_array($stateArr) && !empty($stateArr)){
			if($selected){
				foreach($stateArr as $code=>$name){
					if($selected == $code || strtolower($selected) == strtolower($name)){
						$str .= '<option selected="selected" value="'.$code.'"  rel="'.$name.'">'.$name.'</option>';
					}else{
						$str .= '<option value="'.$code.'"  rel="'.$name.'" >'.$name.'</option>';
					}
				}
			}else{
				foreach($stateArr as $code=>$name){
					$str .= '<option value="'.$code.'" rel="'.$name.'">'.$name.'</option>';
				}
			}
		}
		return $str;
	}
	
	
	//得到所有国家的option
	public  function getAllCountryOptions($name="country",$class="country",$current = ''){
		$all_country_array = $this->getAllCountryArray();
		if($name && $class){
			$str = '<select name="'.$name.'" class="'.$class.'">';
		}
		$str .= '<option value=""></option>';
		foreach($all_country_array as $k=>$v){
			if($current){
				if($k == $current){
					$str .= '<option selected="selected" value="'.$k.'">'.$v.'</option>';
				}else{
					$str .= '<option value="'.$k.'">'.$v.'</option>';
				}
			}else{
				$str .= '<option value="'.$k.'">'.$v.'</option>';
			}
		}
		if($name && $class){
			$str .= "</select>";
		}
		return $str;
	
	}
	
	
	public function getCountryNameByKey($key){
		$all_country = $this->getAllCountryArray();
		return isset($all_country[$key]) ? $all_country[$key] : $key;
	}
	
	
	
	public static function getCountryOptionsHtml($selectd = ''){
		if(!$selectd){
			$selectd = $this->getDefaultCountry();
		}
		
		$all_country = $this->getAllCountryArray();
		$str = '';
		foreach($all_country as $key=>$value){
			if($selectd && ($selectd == $key)){
				$str .= '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			}else{
				$str .= '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		return $str;
	}
	
	
					
	
	
	
	/**
	 * @property $countryCode |String 国家简码
	 * @property $stateCode | String 省市简码
	 * @return String OR Array 如果不传递省市简码，那么返回的是该国家对应的省市数组
	 *		如果传递省市简码，传递的是省市的名称
	 */
	
	public function getStateByContryCode($countryCode,$stateCode=''){
		$countryStates = $this->getCountryStateArr();
		$returnStateArr = [];
		$returnStateName = '';
		if($countryCode){
			if($stateCode){
				if(isset($countryStates[$countryCode][$stateCode]) && !empty($countryStates[$countryCode][$stateCode])){
					$returnStateName = $countryStates[$countryCode][$stateCode];
				}
				return $returnStateName ? $returnStateName : $stateCode;
				
			}else{
				if(isset($countryStates[$countryCode]) && !empty($countryStates[$countryCode]) && is_array($countryStates[$countryCode])){
					$returnStateArr = $countryStates[$countryCode];
				}
				return $returnStateArr;
			}
		}
	}
	
	/**
	 * @return Array ，得到所有国家的数组
	 * 格式：['国家简码' => '国家全称']
	 */
	public static function getAllCountryArray(){
		return [
			"AF"=>"Afghanistan",
			"AX"=>"Åland Islands",
			"AL"=>"Albania",
			"DZ"=>"Algeria",
			"AS"=>"American Samoa",
			"AD"=>"Andorra",
			"AO"=>"Angola",
			"AI"=>"Anguilla",
			"AQ"=>"Antarctica",
			"AG"=>"Antigua and Barbuda",
			"AR"=>"Argentina",
			"AM"=>"Armenia",
			"AW"=>"Aruba",
			"AU"=>"Australia",
			"AT"=>"Austria",
			"AZ"=>"Azerbaijan",
			"BS"=>"Bahamas",
			"BH"=>"Bahrain",
			"BD"=>"Bangladesh",
			"BB"=>"Barbados",
			"BY"=>"Belarus",
			"BE"=>"Belgium",
			"BZ"=>"Belize",
			"BJ"=>"Benin",
			"BM"=>"Bermuda",
			"BT"=>"Bhutan",
			"BO"=>"Bolivia",
			"BA"=>"Bosnia and Herzegovina",
			"BW"=>"Botswana",
			"BV"=>"Bouvet Island",
			"BR"=>"Brazil",
			"IO"=>"British Indian Ocean Territory",
			"VG"=>"British Virgin Islands",
			"BN"=>"Brunei",
			"BG"=>"Bulgaria",
			"BF"=>"Burkina Faso",
			"BI"=>"Burundi",
			"KH"=>"Cambodia",
			"CM"=>"Cameroon",
			"CA"=>"Canada",
			"CV"=>"Cape Verde",
			"KY"=>"Cayman Islands",
			"CF"=>"Central African Republic",
			"TD"=>"Chad",
			"CL"=>"Chile",
			"CN"=>"China",
			"CX"=>"Christmas Island",
			"CC"=>"Cocos [Keeling] Islands",
			"CO"=>"Colombia",
			"KM"=>"Comoros",
			"CG"=>"Congo - Brazzaville",
			"CD"=>"Congo - Kinshasa",
			"CK"=>"Cook Islands",
			"CR"=>"Costa Rica",
			"CI"=>"Côte d’Ivoire",
			"HR"=>"Croatia",
			"CU"=>"Cuba",
			"CY"=>"Cyprus",
			"CZ"=>"Czech Republic",
			"DK"=>"Denmark",
			"DJ"=>"Djibouti",
			"DM"=>"Dominica",
			"DO"=>"Dominican Republic",
			"EC"=>"Ecuador",
			"EG"=>"Egypt",
			"SV"=>"El Salvador",
			"GQ"=>"Equatorial Guinea",
			"ER"=>"Eritrea",
			"EE"=>"Estonia",
			"ET"=>"Ethiopia",
			"FK"=>"Falkland Islands",
			"FO"=>"Faroe Islands",
			"FJ"=>"Fiji",
			"FI"=>"Finland",
			"FR"=>"France",
			"GF"=>"French Guiana",
			"PF"=>"French Polynesia",
			"TF"=>"French Southern Territories",
			"GA"=>"Gabon",
			"GM"=>"Gambia",
			"GE"=>"Georgia",
			"DE"=>"Germany",
			"GH"=>"Ghana",
			"GI"=>"Gibraltar",
			"GR"=>"Greece",
			"GL"=>"Greenland",
			"GD"=>"Grenada",
			"GP"=>"Guadeloupe",
			"GU"=>"Guam",
			"GT"=>"Guatemala",
			"GG"=>"Guernsey",
			"GN"=>"Guinea",
			"GW"=>"Guinea-Bissau",
			"GY"=>"Guyana",
			"HT"=>"Haiti",
			"HM"=>"Heard Island and McDonald Islands",
			"HN"=>"Honduras",
			"HK"=>"Hong Kong SAR China",
			"HU"=>"Hungary",
			"IS"=>"Iceland",
			"IN"=>"India",
			"ID"=>"Indonesia",
			"IR"=>"Iran",
			"IQ"=>"Iraq",
			"IE"=>"Ireland",
			"IM"=>"Isle of Man",
			"IL"=>"Israel",
			"IT"=>"Italy",
			"JM"=>"Jamaica",
			"JP"=>"Japan",
			"JE"=>"Jersey",
			"JO"=>"Jordan",
			"KZ"=>"Kazakhstan",
			"KE"=>"Kenya",
			"KI"=>"Kiribati",
			"KW"=>"Kuwait",
			"KG"=>"Kyrgyzstan",
			"LA"=>"Laos",
			"LV"=>"Latvia",
			"LB"=>"Lebanon",
			"LS"=>"Lesotho",
			"LR"=>"Liberia",
			"LY"=>"Libya",
			"LI"=>"Liechtenstein",
			"LT"=>"Lithuania",
			"LU"=>"Luxembourg",
			"MO"=>"Macau SAR China",
			"MK"=>"Macedonia",
			"MG"=>"Madagascar",
			"MW"=>"Malawi",
			"MY"=>"Malaysia",
			"MV"=>"Maldives",
			"ML"=>"Mali",
			"MT"=>"Malta",
			"MH"=>"Marshall Islands",
			"MQ"=>"Martinique",
			"MR"=>"Mauritania",
			"MU"=>"Mauritius",
			"YT"=>"Mayotte",
			"MX"=>"Mexico",
			"FM"=>"Micronesia",
			"MD"=>"Moldova",
			"MC"=>"Monaco",
			"MN"=>"Mongolia",
			"ME"=>"Montenegro",
			"MS"=>"Montserrat",
			"MA"=>"Morocco",
			"MZ"=>"Mozambique",
			"MM"=>"Myanmar [Burma]",
			"NA"=>"Namibia",
			"NR"=>"Nauru",
			"NP"=>"Nepal",
			"NL"=>"Netherlands",
			"AN"=>"Netherlands Antilles",
			"NC"=>"New Caledonia",
			"NZ"=>"New Zealand",
			"NI"=>"Nicaragua",
			"NE"=>"Niger",
			"NG"=>"Nigeria",
			"NU"=>"Niue",
			"NF"=>"Norfolk Island",
			"MP"=>"Northern Mariana Islands",
			"KP"=>"North Korea",
			"NO"=>"Norway",
			"OM"=>"Oman",
			"PK"=>"Pakistan",
			"PW"=>"Palau",
			"PS"=>"Palestinian Territories",
			"PA"=>"Panama",
			"PG"=>"Papua New Guinea",
			"PY"=>"Paraguay",
			"PE"=>"Peru",
			"PH"=>"Philippines",
			"PN"=>"Pitcairn Islands",
			"PL"=>"Poland",
			"PT"=>"Portugal",
			"PR"=>"Puerto Rico",
			"QA"=>"Qatar",
			"RE"=>"R¨¦union",
			"RO"=>"Romania",
			"RU"=>"Russia",
			"RW"=>"Rwanda",
			"BL"=>"Saint Barth¨¦lemy",
			"SH"=>"Saint Helena",
			"KN"=>"Saint Kitts and Nevis",
			"LC"=>"Saint Lucia",
			"MF"=>"Saint Martin",
			"PM"=>"Saint Pierre and Miquelon",
			"VC"=>"Saint Vincent and the Grenadines",
			"WS"=>"Samoa",
			"SM"=>"San Marino",
			"ST"=>"São Tomé and Príncipe",
			"SA"=>"Saudi Arabia",
			"SN"=>"Senegal",
			"RS"=>"Serbia",
			"SC"=>"Seychelles",
			"SL"=>"Sierra Leone",
			"SG"=>"Singapore",
			"SK"=>"Slovakia",
			"SI"=>"Slovenia",
			"SB"=>"Solomon Islands",
			"SO"=>"Somalia",
			"ZA"=>"South Africa",
			"GS"=>"South Georgia and the South Sandwich Islands",
			"KR"=>"South Korea",
			"ES"=>"Spain",
			"LK"=>"Sri Lanka",
			"SD"=>"Sudan",
			"SR"=>"Suriname",
			"SJ"=>"Svalbard and Jan Mayen",
			"SZ"=>"Swaziland",
			"SE"=>"Sweden",
			"CH"=>"Switzerland",
			"SY"=>"Syria",
			"TW"=>"Taiwan",
			"TJ"=>"Tajikistan",
			"TZ"=>"Tanzania",
			"TH"=>"Thailand",
			"TL"=>"Timor-Leste",
			"TG"=>"Togo",
			"TK"=>"Tokelau",
			"TO"=>"Tonga",
			"TT"=>"Trinidad and Tobago",
			"TN"=>"Tunisia",
			"TR"=>"Turkey",
			"TM"=>"Turkmenistan",
			"TC"=>"Turks and Caicos Islands",
			"TV"=>"Tuvalu",
			"UG"=>"Uganda",
			"UA"=>"Ukraine",
			"AE"=>"United Arab Emirates",
			"GB"=>"United Kingdom",
			"US"=>"United States",
			"UY"=>"Uruguay",
			"UM"=>"U.S. Minor Outlying Islands",
			"VI"=>"U.S. Virgin Islands",
			"UZ"=>"Uzbekistan",
			"VU"=>"Vanuatu",
			"VA"=>"Vatican City",
			"VE"=>"Venezuela",
			"VN"=>"Vietnam",
			"WF"=>"Wallis and Futuna",
			"EH"=>"Western Sahara",
			"YE"=>"Yemen",
			"ZM"=>"Zambia",
			"ZW"=>"Zimbabwe",
		];
	
	}
	
	/**
	 * 得到国家和省市数组
	 * 格式为： [
	 *				国家简码 => 
	 *					[
	 *						省/市简码 => 省/市名称,
	 *						省/市简码 => 省/市名称,
	 *						省/市简码 => 省/市名称,
	 *					]
	 *				]
	 *			]
	 * 在选择国家后，省市的信息会以ajax的形式带出，存在以下列表的国家，会以下拉选择条
	 * 的方式显示，如果不存在以下列表，则显示inputtext输入框，如果您想要某个国家的省市以
	 * 下拉条的方式选择，可以在下面的函数里面添加对应的国家和省市信息，添加后
	 * 选择国家后，省市会以下拉条的方式供用户选择，而不是inputtext填写省市信息。
	 */
	public  function getCountryStateArr(){
		$data = [
			'US' => [
				'AL' => 'Alabama', 
				'AK' => 'Alaska', 
				'AS' => 'American Samoa',
				'AZ' => 'Arizona', 
				'AR' => 'Arkansas', 
				'AF' => 'Armed Forces Africa',
				'AA' => 'Armed Forces Americas', 
				'AC' => 'Armed Forces Canada',
				'AE' => 'Armed Forces Europe', 
				'AM' => 'Armed Forces Middle East',
				'AP' => 'Armed Forces Pacific', 
				'CA' => 'California', 
				'CO' => 'Colorado',
				'CT' => 'Connecticut', 
				'DE' => 'Delaware', 
				'DC' => 'District of Columbia',
				'FM' => 'Federated States Of Micronesia', 
				'FL' => 'Florida', 
				'GA' => 'Georgia',
				'GU' => 'Guam', 
				'HI' => 'Hawaii', 
				'ID' => 'Idaho', 
				'IL' => 'Illinois',
				'IN' => 'Indiana', 
				'IA' => 'Iowa', 
				'KS' => 'Kansas', 
				'KY' => 'Kentucky',
				'LA' => 'Louisiana', 
				'ME' => 'Maine', 
				'MH' => 'Marshall Islands',
				'MD' => 'Maryland', 
				'MA' => 'Massachusetts', 
				'MI' => 'Michigan',
				'MN' => 'Minnesota', 
				'MS' => 'Mississippi', 
				'MO' => 'Missouri',
				'MT' => 'Montana', 
				'NE' => 'Nebraska', 
				'NV' => 'Nevada',
				'NH' => 'New Hampshire', 
				'NJ' => 'New Jersey', 
				'NM' => 'New Mexico',
				'NY' => 'New York', 
				'NC' => 'North Carolina', 
				'ND' => 'North Dakota',
				'MP' => 'Northern Mariana Islands', 
				'OH' => 'Ohio', 
				'OK' => 'Oklahoma',
				'OR' => 'Oregon', 
				'PW' => 'Palau', 
				'PA' => 'Pennsylvania',
				'PR' => 'Puerto Rico', 
				'RI' => 'Rhode Island', 
				'SC' => 'South Carolina',
				'SD' => 'South Dakota', 
				'TN' => 'Tennessee', 
				'TX' => 'Texas',
				'UT' => 'Utah', 
				'VT' => 'Vermont', 
				'VI' => 'Virgin Islands',
				'VA' => 'Virginia', 
				'WA' => 'Washington', 
				'WV' => 'West Virginia',
				'WI' => 'Wisconsin', 
				'WY' => 'Wyoming',
			],
			'CA' => [
				'AB' => 'Alberta',
				'BC' => 'British Columbia', 
				'MB' => 'Manitoba',
				'NL' => 'Newfoundland and Labrador', 
				'NB' => 'New Brunswick',
				'NS' => 'Nova Scotia', 
				'NT' => 'Northwest Territories', 
				'NU' => 'Nunavut',
				'ON' => 'Ontario', 
				'PE' => 'Prince Edward Island', 
				'QC' => 'Quebec',
				'SK' => 'Saskatchewan', 
				'YT' => 'Yukon Territory',
			],
			'DE' => [
				'NDS' => 'Niedersachsen',
				'BAW' => 'Baden-Württemberg', 
				'BAY' => 'Bayern', 
				'BER' => 'Berlin',
				'BRG' => 'Brandenburg', 
				'BRE' => 'Bremen', 
				'HAM' => 'Hamburg',
				'HES' => 'Hessen', 
				'MEC' => 'Mecklenburg-Vorpommern',
				'NRW' => 'Nordrhein-Westfalen', 
				'RHE' => 'Rheinland-Pfalz', 
				'SAR' => 'Saarland',
				'SAS' => 'Sachsen', 
				'SAC' => 'Sachsen-Anhalt', 
				'SCN' => 'Schleswig-Holstein',
				'THE' => 'Thüringen', 
			],
			'AT' => [	
				'WI' => 'Wien',
				'NO' => 'Niederösterreich',
				'OO' => 'Oberösterreich', 
				'SB' => 'Salzburg', 
				'KN' => 'Kärnten',
				'ST' => 'Steiermark', 
				'TI' => 'Tirol', 
				'BL' => 'Burgenland',
				'VB' => 'Voralberg', 
			],
			'CH' => [	
				'AG' => 'Aargau', 
				'AI' => 'Appenzell Innerrhoden',
				'AR' => 'Appenzell Ausserrhoden',
				'BE' => 'Bern', 
				'BL' => 'Basel-Landschaft',
				'BS' => 'Basel-Stadt', 
				'FR' => 'Freiburg',
				'GE' => 'Genf',
				'GL' => 'Glarus', 
				'GR' => 'Graubünden', 
				'JU' => 'Jura',
				'LU' => 'Luzern', 
				'NE' => 'Neuenburg', 
				'NW' => 'Nidwalden',
				'OW' => 'Obwalden', 
				'SG' => 'St. Gallen', 
				'SH' => 'Schaffhausen',
				'SO' => 'Solothurn', 
				'SZ' => 'Schwyz', 
				'TG' => 'Thurgau',
				'TI' => 'Tessin', 
				'UR' => 'Uri', 
				'VD' => 'Waadt', 
				'VS' => 'Wallis',
				'ZG' => 'Zug', 
				'ZH' => 'Zürich', 
			],
			'ES' => [	
				'A Coruсa' => 'A Coruña',
				'Alava' => 'Alava', 
				'Albacete' => 'Albacete', 
				'Alicante' => 'Alicante',
				'Almeria' => 'Almeria', 
				'Asturias' => 'Asturias', 
				'Avila' => 'Avila',
				'Badajoz' => 'Badajoz', 
				'Baleares' => 'Baleares', 
				'Barcelona' => 'Barcelona',
				'Burgos' => 'Burgos', 
				'Caceres' => 'Caceres', 
				'Cadiz' => 'Cadiz',
				'Cantabria' => 'Cantabria', 
				'Castellon' => 'Castellon', 
				'Ceuta' => 'Ceuta',
				'Ciudad Real' => 'Ciudad Real', 
				'Cordoba' => 'Cordoba', 
				'Cuenca' => 'Cuenca',
				'Girona' => 'Girona', 
				'Granada' => 'Granada', 
				'Guadalajara' => 'Guadalajara',
				'Guipuzcoa' => 'Guipuzcoa', 
				'Huelva' => 'Huelva', 
				'Huesca' => 'Huesca',
				'Jaen' => 'Jaen', 
				'La Rioja' => 'La Rioja', 
				'Las Palmas' => 'Las Palmas',
				'Leon' => 'Leon', 
				'Lleida' => 'Lleida', 
				'Lugo' => 'Lugo',
				'Madrid' => 'Madrid', 
				'Malaga' => 'Malaga', 
				'Melilla' => 'Melilla',
				'Murcia' => 'Murcia', 
				'Navarra' => 'Navarra', 
				'Ourense' => 'Ourense',
				'Palencia' => 'Palencia', 
				'Pontevedra' => 'Pontevedra', 
				'Salamanca' => 'Salamanca',
				'Santa Cruz de Tenerife' => 'Santa Cruz de Tenerife', 
				'Segovia' => 'Segovia',
				'Sevilla' => 'Sevilla', 
				'Soria' => 'Soria', 
				'Tarragona' => 'Tarragona',
				'Teruel' => 'Teruel', 
				'Toledo' => 'Toledo', 
				'Valencia' => 'Valencia',
				'Valladolid' => 'Valladolid', 
				'Vizcaya' => 'Vizcaya', 
				'Zamora' => 'Zamora',
				'Zaragoza' => 'Zaragoza', 
			],
			'FR' => [	
				'1' => 'Ain', 
				'2' => 'Aisne', 
				'3' => 'Allier',
				'4' => 'Alpes-de-Haute-Provence', 
				'5' => 'Hautes-Alpes', 
				'6' => 'Alpes-Maritimes',
				'7' => 'Ardèche', 
				'8' => 'Ardennes', 
				'9' => 'Ariège', 
				'10' => 'Aube',
				'11' => 'Aude', 
				'12' => 'Aveyron', 
				'13' => 'Bouches-du-Rhône',
				'14' => 'Calvados', 
				'15' => 'Cantal', 
				'16' => 'Charente',
				'17' => 'Charente-Maritime', 
				'18' => 'Cher', 
				'19' => 'Corrèze',
				'2A' => 'Corse-du-Sud', 
				'2B' => 'Haute-Corse', 
				'21' => 'Côte-d\'Or',
				'22' => 'Côtes-d\'Armor', 
				'23' => 'Creuse', 
				'24' => 'Dordogne', 
				'25' => 'Doubs',
				'26' => 'Drôme', 
				'27' => 'Eure', 
				'28' => 'Eure-et-Loir', 
				'29' => 'Finistère',
				'30' => 'Gard', 
				'31' => 'Haute-Garonne', 
				'32' => 'Gers', 
				'33' => 'Gironde',
				'34' => 'Hérault', 
				'35' => 'Ille-et-Vilaine',
				'36' => 'Indre',
				'37' => 'Indre-et-Loire', 
				'38' => 'Isère', 
				'39' => 'Jura',
				'40' => 'Landes',
				'41' => 'Loir-et-Cher', 
				'42' => 'Loire', 
				'43' => 'Haute-Loire',
				'44' => 'Loire-Atlantique', 
				'45' => 'Loiret', 
				'46' => 'Lot',
				'47' => 'Lot-et-Garonne',
				'48' => 'Lozère', 
				'49' => 'Maine-et-Loire',
				'50' => 'Manche', 
				'51' => 'Marne',
				'52' => 'Haute-Marne', 
				'53' => 'Mayenne',
				'54' => 'Meurthe-et-Moselle', 
				'55' => 'Meuse', 
				'56' => 'Morbihan',
				'57' => 'Moselle', 
				'58' => 'Nièvre', 
				'59' => 'Nord', 
				'60' => 'Oise',
				'61' => 'Orne', 
				'62' => 'Pas-de-Calais', 
				'63' => 'Puy-de-Dôme',
				'64' => 'Pyrénées-Atlantiques',
				'65' => 'Hautes-Pyrénées',
				'66' => 'Pyrénées-Orientales',
				'67' => 'Bas-Rhin', 
				'68' => 'Haut-Rhin', 
				'69' => 'Rhône', 
				'70' => 'Haute-Saône',
				'71' => 'Saône-et-Loire', 
				'72' => 'Sarthe', 
				'73' => 'Savoie',
				'74' => 'Haute-Savoie', 
				'75' => 'Paris', 
				'76' => 'Seine-Maritime',
				'77' => 'Seine-et-Marne',
				'78' => 'Yvelines', 
				'79' => 'Deux-Sèvres',
				'80' => 'Somme', 
				'81' => 'Tarn', 
				'82' => 'Tarn-et-Garonne',
				'83' => 'Var',
				'84' => 'Vaucluse', 
				'85' => 'Vendée', 
				'86' => 'Vienne', 
				'87' => 'Haute-Vienne',
				'88' => 'Vosges', 
				'89' => 'Yonne', 
				'90' => 'Territoire-de-Belfort',
				'91' => 'Essonne', 
				'92' => 'Hauts-de-Seine', 
				'93' => 'Seine-Saint-Denis',
				'94' => 'Val-de-Marne', 
				'95' => 'Val-d\'Oise',
			],
			'RO' => [
				'AB' => 'Alba',
				'AR' => 'Arad', 
				'AG' => 'Argeş', 
				'BC' => 'Bacău', 
				'BH' => 'Bihor',
				'BN' => 'Bistriţa-Năsăud', 
				'BT' => 'Botoşani', 
				'BV' => 'Braşov',
				'BR' => 'Brăila', 
				'B' => 'Bucureşti', 
				'BZ' => 'Buzău',
				'CS' => 'Caraş-Severin', 
				'CL' => 'Călăraşi', 
				'CJ' => 'Cluj',
				'CT' => 'Constanţa', 
				'CV' => 'Covasna',
				'DB' => 'Dâmboviţa',
				'DJ' => 'Dolj', 
				'GL' => 'Galaţi', 
				'GR' => 'Giurgiu', 
				'GJ' => 'Gorj',
				'HR' => 'Harghita', 
				'HD' => 'Hunedoara', 
				'IL' => 'Ialomiţa',
				'IS' => 'Iaşi', 
				'IF' => 'Ilfov', 
				'MM' => 'Maramureş',
				'MH' => 'Mehedinţi', 
				'MS' => 'Mureş', 
				'NT' => 'Neamţ', 
				'OT' => 'Olt',
				'PH' => 'Prahova', 
				'SM' => 'Satu-Mare', 
				'SJ' => 'Sălaj',
				'SB' => 'Sibiu', 
				'SV' => 'Suceava', 
				'TR' => 'Teleorman',
				'TM' => 'Timiş', 
				'TL' => 'Tulcea', 
				'VS' => 'Vaslui',
				'VL' => 'Vâlcea',
				'VN' => 'Vrancea',
			],
			'FI' => [
				'Lappi' => 'Lappi',
				'Pohjois-Pohjanmaa' => 'Pohjois-Pohjanmaa',
				'Kainuu' => 'Kainuu',
				'Pohjois-Karjala' => 'Pohjois-Karjala', 
				'Pohjois-Savo' => 'Pohjois-Savo',
				'Etelä-Savo' => 'Etelä-Savo', 
				'Etelä-Pohjanmaa' => 'Etelä-Pohjanmaa',
				'Pohjanmaa' => 'Pohjanmaa', 
				'Pirkanmaa' => 'Pirkanmaa', 
				'Satakunta' => 'Satakunta',
				'Keski-Pohjanmaa' => 'Keski-Pohjanmaa', 
				'Keski-Suomi' => 'Keski-Suomi',
				'Varsinais-Suomi' => 'Varsinais-Suomi',
				'Etelä-Karjala' => 'Etelä-Karjala',
				'Päijät-Häme' => 'Päijät-Häme', 
				'Kanta-Häme' => 'Kanta-Häme',
				'Uusimaa' => 'Uusimaa', 
				'Itä-Uusimaa' => 'Itä-Uusimaa',
				'Kymenlaakso' => 'Kymenlaakso', 
				'Ahvenanmaa' => 'Ahvenanmaa',
			],
			'EE' => [	
				'EE-37' => 'Harjumaa', 
				'EE-39' => 'Hiiumaa', 
				'EE-44' => 'Ida-Virumaa',
				'EE-49' => 'Jõgevamaa', 
				'EE-51' => 'Järvamaa', 
				'EE-57' => 'Läänemaa',
				'EE-59' => 'Lääne-Virumaa',
				'EE-65' => 'Põlvamaa', 
				'EE-67' => 'Pärnumaa',
				'EE-70' => 'Raplamaa', 
				'EE-74' => 'Saaremaa', 
				'EE-78' => 'Tartumaa',
				'EE-82' => 'Valgamaa', 
				'EE-84' => 'Viljandimaa', 
				'EE-86' => 'Võrumaa',
			],
			'LV' => [	
				'LV-DGV' => 'Daugavpils', 
				'LV-JEL' => 'Jelgava', 
				'Jēkabpils' => 'Jēkabpils',
				'LV-JUR' => 'Jūrmala', 
				'LV-LPX' => 'Liepāja', 
				'LV-LE' => 'Liepājas novads',
				'LV-REZ' => 'Rēzekne',
				'LV-RIX' => 'Rīga', 
				'LV-RI' => 'Rīgas novads',
				'Valmiera' => 'Valmiera', 
				'LV-VEN' => 'Ventspils',
				'Aglonas novads' => 'Aglonas novads',
				'LV-AI' => 'Aizkraukles novads',
				'Aizputes novads' => 'Aizputes novads', 
				'Aknīstes novads' => 'Aknīstes novads',
				'Alojas novads' => 'Alojas novads', 
				'Alsungas novads' => 'Alsungas novads',
				'LV-AL' => 'Alūksnes novads', 
				'Amatas novads' => 'Amatas novads',
				'Apes novads' => 'Apes novads',
				'Auces novads' => 'Auces novads',
				'Babītes novads' => 'Babītes novads', 
				'Baldones novads' => 'Baldones novads',
				'Baltinavas novads' => 'Baltinavas novads',
				'LV-BL' => 'Balvu novads',
				'LV-BU' => 'Bauskas novads', 
				'Beverīnas novads' => 'Beverīnas novads',
				'Brocēnu novads' => 'Brocēnu novads',
				'Burtnieku novads' => 'Burtnieku novads',
				'Carnikavas novads' => 'Carnikavas novads', 
				'Cesvaines novads' => 'Cesvaines novads',
				'Ciblas novads' => 'Ciblas novads', 
				'LV-CE' => 'Cēsu novads',
				'Dagdas novads' => 'Dagdas novads', 
				'LV-DA' => 'Daugavpils novads',
				'LV-DO' => 'Dobeles novads', 
				'Dundagas novads' => 'Dundagas novads',
				'Durbes novads' => 'Durbes novads',
				'Engures novads' => 'Engures novads',
				'Garkalnes novads' => 'Garkalnes novads',
				'Grobiņas novads' => 'Grobiņas novads',
				'LV-GU' => 'Gulbenes novads', 
				'Iecavas novads' => 'Iecavas novads',
				'Ikšķiles novads' => 'Ikšķiles novads', 
				'Ilūkstes novads' => 'Ilūkstes novads',
				'Inčukalna novads' => 'Inčukalna novads',
				'Jaunjelgavas novads' => 'Jaunjelgavas novads',
				'Jaunpiebalgas novads' => 'Jaunpiebalgas novads',
				'Jaunpils novads' => 'Jaunpils novads',
				'LV-JL' => 'Jelgavas novads', 
				'LV-JK' => 'Jēkabpils novads',
				'Kandavas novads' => 'Kandavas novads',
				'Kokneses novads' => 'Kokneses novads',
				'Krimuldas novads' => 'Krimuldas novads',
				'Krustpils novads' => 'Krustpils novads',
				'LV-KR' => 'Krāslavas novads', 
				'LV-KU' => 'Kuldīgas novads',
				'Kārsavas novads' => 'Kārsavas novads', 
				'Lielvārdes novads' => 'Lielvārdes novads',
				'LV-LM' => 'Limbažu novads', 
				'Lubānas novads' => 'Lubānas novads',
				'LV-LU' => 'Ludzas novads', 
				'Līgatnes novads' => 'Līgatnes novads',
				'Līvānu novads' => 'Līvānu novads',
				'LV-MA' => 'Madonas novads',
				'Mazsalacas novads' => 'Mazsalacas novads',
				'Mālpils novads' => 'Mālpils novads',
				'Mārupes novads' => 'Mārupes novads', 
				'Naukšēnu novads' => 'Naukšēnu novads',
				'Neretas novads' => 'Neretas novads', 
				'Nīcas novads' => 'Nīcas novads',
				'LV-OG' => 'Ogres novads', 
				'Olaines novads' => 'Olaines novads',
				'Ozolnieku novads' => 'Ozolnieku novads',
				'LV-PR' => 'Preiļu novads',
				'Priekules novads' => 'Priekules novads',
				'Priekuļu novads' => 'Priekuļu novads',
				'Pārgaujas novads' => 'Pārgaujas novads', 
				'Pāvilostas novads' => 'Pāvilostas novads',
				'Pļaviņu novads' => 'Pļaviņu novads', 
				'Raunas novads' => 'Raunas novads',
				'Riebiņu novads' => 'Riebiņu novads',
				'Rojas novads' => 'Rojas novads',
				'Ropažu novads' => 'Ropažu novads', 
				'Rucavas novads' => 'Rucavas novads',
				'Rugāju novads' => 'Rugāju novads',
				'Rundāles novads' => 'Rundāles novads',
				'LV-RE' => 'Rēzeknes novads', 
				'Rūjienas novads' => 'Rūjienas novads',
				'Salacgrīvas novads' => 'Salacgrīvas novads', 
				'Salas novads' => 'Salas novads',
				'Salaspils novads' => 'Salaspils novads',
				'LV-SA' => 'Saldus novads',
				'Saulkrastu novads' => 'Saulkrastu novads', 
				'Siguldas novads' => 'Siguldas novads',
				'Skrundas novads' => 'Skrundas novads',
				'Skrīveru novads' => 'Skrīveru novads',
				'Smiltenes novads' => 'Smiltenes novads',
				'Stopiņu novads' => 'Stopiņu novads',
				'Strenču novads' => 'Strenču novads',
				'Sējas novads' => 'Sējas novads',
				'LV-TA' => 'Talsu novads',
				'LV-TU' => 'Tukuma novads',
				'Tērvetes novads' => 'Tērvetes novads', 
				'Vaiņodes novads' => 'Vaiņodes novads',
				'LV-VK' => 'Valkas novads', 
				'LV-VM' => 'Valmieras novads',
				'Varakļānu novads' => 'Varakļānu novads',
				'Vecpiebalgas novads' => 'Vecpiebalgas novads',
				'Vecumnieku novads' => 'Vecumnieku novads',
				'LV-VE' => 'Ventspils novads',
				'Viesītes novads' => 'Viesītes novads', 
				'Viļakas novads' => 'Viļakas novads',
				'Viļānu novads' => 'Viļānu novads',
				'Vārkavas novads' => 'Vārkavas novads',
				'Zilupes novads' => 'Zilupes novads', 
				'Ādažu novads' => 'Ādažu novads',
				'Ērgļu novads' => 'Ērgļu novads',
				'Ķeguma novads' => 'Ķeguma novads',
				'Ķekavas novads' => 'Ķekavas novads', 
			],
			'LT' => [	
				'LT-AL' => 'Alytaus Apskritis',
				'LT-KU' => 'Kauno Apskritis', 
				'LT-KL' => 'Klaipėdos Apskritis',
				'LT-MR' => 'Marijampolės Apskritis', 
				'LT-PN' => 'Panevėžio Apskritis',
				'LT-SA' => 'Šiaulių Apskritis', 
				'LT-TA' => 'Tauragės Apskritis',
				'LT-TE' => 'Telšių Apskritis', 
				'LT-UT' => 'Utenos Apskritis',
				'LT-VL' => 'Vilniaus Apskritis',
			],
			
			'CN' => [
				'BJ' => '北京市',
				'SH' => '上海市',
				'TJ' => '天津市',
				'CQ' => '重庆市',
				'HEB' => '河北省',
				'SAX' => '山西省',
				'LN' => '辽宁省',
				'JL' => '吉林省',
				'HLJ' => '黑龙江省',
				'JS' => '江苏省',
				'ZJ' => '浙江省',
				'AH' => '安徽省',
				'FJ' => '福建省',
				'JX' => '江西省',
				'SD' => '山东省',
				'HEN' => '河南省',
				'HUB' => '湖北省',
				'HUN' => '湖南省',
				'GD' => '广东省',
				'HN' => '海南省',
				'SC' => '四川省',
				'HZ' => '贵州省',
				'YN' => '云南省',
				'SNX' => '陕西省',
				'GS' => '甘肃省',
				'QH' => '青海省',
				'TW' => '台湾省',
				'GX' => '广西壮族自治区',
				'NMG' => '内蒙古自治区',
				'XZ' => '西藏自治区',
				'NX' => '宁夏回族自治区',
				'XJ' => '新疆维吾尔自治区',
				'XG' => '香港特别行政区',
				
			],
		






		




		];
		return $data;
	}
	
	
	
}