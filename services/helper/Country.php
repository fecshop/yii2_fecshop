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
	
	/*
	public function getOnepageCheckoutCountrySelectHtml($selectd = ''){
		
		$str  = '<select title="Country" class="billing_country validate-select" id="billing:country" name="billing[country]">';
		//$str .= '<option value=""> </option>';
		$str .= self::getCountryOptionsHtml($selectd);
		$str .= '</select>';
		return $str;
	}
	*/
	
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
	 * @return String 如果不传递省市简码，那么返回的是该国家对应的省市
	 *		如果传递省市简码，传递的是省市的全称
	 */
	
	public function getStateByContryCode($countryCode,$stateCode=''){
		$countrys = $this->getCountryStateArr();
		$stateArr = [];
		if($countryCode){
			if(!$stateCode){
				foreach($countrys as $co){
					if($co[0] == $countryCode){
						$stateArr[$co[1]] = $co[2];
					}
				}
				return $stateArr;
			}else{
				foreach($countrys as $co){
					if($co[0] == $countryCode){
						if($co[1] == $stateCode){
							return $co[2];
						}
					}
				}
				return $stateCode;
			}
		}
		
	}
	
	
	//得到所有国家的数组
	public static function getAllCountryArray(){
		return array(
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
			"CI"=>"C?te d¡¯Ivoire",
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
			"ST"=>"S?o Tom¨¦ and Pr¨ªncipe",
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
		);
	
	}
	
	
	
	
	public  function getCountryStateArr(){
		$data = [
			array('US', 'AL', 'Alabama'), array('US', 'AK', 'Alaska'), array('US', 'AS', 'American Samoa'),
					array('US', 'AZ', 'Arizona'), array('US', 'AR', 'Arkansas'), array('US', 'AF', 'Armed Forces Africa'),
					array('US', 'AA', 'Armed Forces Americas'), array('US', 'AC', 'Armed Forces Canada'),
					array('US', 'AE', 'Armed Forces Europe'), array('US', 'AM', 'Armed Forces Middle East'),
					array('US', 'AP', 'Armed Forces Pacific'), array('US', 'CA', 'California'), array('US', 'CO', 'Colorado'),
					array('US', 'CT', 'Connecticut'), array('US', 'DE', 'Delaware'), array('US', 'DC', 'District of Columbia'),
					array('US', 'FM', 'Federated States Of Micronesia'), array('US', 'FL', 'Florida'), array('US', 'GA', 'Georgia'),
					array('US', 'GU', 'Guam'), array('US', 'HI', 'Hawaii'), array('US', 'ID', 'Idaho'), array('US', 'IL', 'Illinois'),
					array('US', 'IN', 'Indiana'), array('US', 'IA', 'Iowa'), array('US', 'KS', 'Kansas'), array('US', 'KY', 'Kentucky'),
					array('US', 'LA', 'Louisiana'), array('US', 'ME', 'Maine'), array('US', 'MH', 'Marshall Islands'),
					array('US', 'MD', 'Maryland'), array('US', 'MA', 'Massachusetts'), array('US', 'MI', 'Michigan'),
					array('US', 'MN', 'Minnesota'), array('US', 'MS', 'Mississippi'), array('US', 'MO', 'Missouri'),
					array('US', 'MT', 'Montana'), array('US', 'NE', 'Nebraska'), array('US', 'NV', 'Nevada'),
					array('US', 'NH', 'New Hampshire'), array('US', 'NJ', 'New Jersey'), array('US', 'NM', 'New Mexico'),
					array('US', 'NY', 'New York'), array('US', 'NC', 'North Carolina'), array('US', 'ND', 'North Dakota'),
					array('US', 'MP', 'Northern Mariana Islands'), array('US', 'OH', 'Ohio'), array('US', 'OK', 'Oklahoma'),
					array('US', 'OR', 'Oregon'), array('US', 'PW', 'Palau'), array('US', 'PA', 'Pennsylvania'),
					array('US', 'PR', 'Puerto Rico'), array('US', 'RI', 'Rhode Island'), array('US', 'SC', 'South Carolina'),
					array('US', 'SD', 'South Dakota'), array('US', 'TN', 'Tennessee'), array('US', 'TX', 'Texas'),
					array('US', 'UT', 'Utah'), array('US', 'VT', 'Vermont'), array('US', 'VI', 'Virgin Islands'),
					array('US', 'VA', 'Virginia'), array('US', 'WA', 'Washington'), array('US', 'WV', 'West Virginia'),
					array('US', 'WI', 'Wisconsin'), array('US', 'WY', 'Wyoming'),

					array('CA', 'AB', 'Alberta'),
					array('CA', 'BC', 'British Columbia'), array('CA', 'MB', 'Manitoba'),
					array('CA', 'NL', 'Newfoundland and Labrador'), array('CA', 'NB', 'New Brunswick'),
					array('CA', 'NS', 'Nova Scotia'), array('CA', 'NT', 'Northwest Territories'), array('CA', 'NU', 'Nunavut'),
					array('CA', 'ON', 'Ontario'), array('CA', 'PE', 'Prince Edward Island'), array('CA', 'QC', 'Quebec'),
					array('CA', 'SK', 'Saskatchewan'), array('CA', 'YT', 'Yukon Territory'),

					array('DE', 'NDS', 'Niedersachsen'),
					array('DE', 'BAW', 'Baden-Württemberg'), array('DE', 'BAY', 'Bayern'), array('DE', 'BER', 'Berlin'),
					array('DE', 'BRG', 'Brandenburg'), array('DE', 'BRE', 'Bremen'), array('DE', 'HAM', 'Hamburg'),
					array('DE', 'HES', 'Hessen'), array('DE', 'MEC', 'Mecklenburg-Vorpommern'),
					array('DE', 'NRW', 'Nordrhein-Westfalen'), array('DE', 'RHE', 'Rheinland-Pfalz'), array('DE', 'SAR', 'Saarland'),
					array('DE', 'SAS', 'Sachsen'), array('DE', 'SAC', 'Sachsen-Anhalt'), array('DE', 'SCN', 'Schleswig-Holstein'),
					array('DE', 'THE', 'Thüringen'), 
					
					array('AT', 'WI', 'Wien'), array('AT', 'NO', 'Niederösterreich'),
					array('AT', 'OO', 'Oberösterreich'), array('AT', 'SB', 'Salzburg'), array('AT', 'KN', 'Kärnten'),
					array('AT', 'ST', 'Steiermark'), array('AT', 'TI', 'Tirol'), array('AT', 'BL', 'Burgenland'),
					array('AT', 'VB', 'Voralberg'), 
					
					array('CH', 'AG', 'Aargau'), array('CH', 'AI', 'Appenzell Innerrhoden'),
					array('CH', 'AR', 'Appenzell Ausserrhoden'), array('CH', 'BE', 'Bern'), array('CH', 'BL', 'Basel-Landschaft'),
					array('CH', 'BS', 'Basel-Stadt'), array('CH', 'FR', 'Freiburg'), array('CH', 'GE', 'Genf'),
					array('CH', 'GL', 'Glarus'), array('CH', 'GR', 'Graubünden'), array('CH', 'JU', 'Jura'),
					array('CH', 'LU', 'Luzern'), array('CH', 'NE', 'Neuenburg'), array('CH', 'NW', 'Nidwalden'),
					array('CH', 'OW', 'Obwalden'), array('CH', 'SG', 'St. Gallen'), array('CH', 'SH', 'Schaffhausen'),
					array('CH', 'SO', 'Solothurn'), array('CH', 'SZ', 'Schwyz'), array('CH', 'TG', 'Thurgau'),
					array('CH', 'TI', 'Tessin'), array('CH', 'UR', 'Uri'), array('CH', 'VD', 'Waadt'), array('CH', 'VS', 'Wallis'),
					array('CH', 'ZG', 'Zug'), array('CH', 'ZH', 'Zürich'), 
					
					array('ES', 'A Coruсa', 'A Coruña'),
					array('ES', 'Alava', 'Alava'), array('ES', 'Albacete', 'Albacete'), array('ES', 'Alicante', 'Alicante'),
					array('ES', 'Almeria', 'Almeria'), array('ES', 'Asturias', 'Asturias'), array('ES', 'Avila', 'Avila'),
					array('ES', 'Badajoz', 'Badajoz'), array('ES', 'Baleares', 'Baleares'), array('ES', 'Barcelona', 'Barcelona'),
					array('ES', 'Burgos', 'Burgos'), array('ES', 'Caceres', 'Caceres'), array('ES', 'Cadiz', 'Cadiz'),
					array('ES', 'Cantabria', 'Cantabria'), array('ES', 'Castellon', 'Castellon'), array('ES', 'Ceuta', 'Ceuta'),
					array('ES', 'Ciudad Real', 'Ciudad Real'), array('ES', 'Cordoba', 'Cordoba'), array('ES', 'Cuenca', 'Cuenca'),
					array('ES', 'Girona', 'Girona'), array('ES', 'Granada', 'Granada'), array('ES', 'Guadalajara', 'Guadalajara'),
					array('ES', 'Guipuzcoa', 'Guipuzcoa'), array('ES', 'Huelva', 'Huelva'), array('ES', 'Huesca', 'Huesca'),
					array('ES', 'Jaen', 'Jaen'), array('ES', 'La Rioja', 'La Rioja'), array('ES', 'Las Palmas', 'Las Palmas'),
					array('ES', 'Leon', 'Leon'), array('ES', 'Lleida', 'Lleida'), array('ES', 'Lugo', 'Lugo'),
					array('ES', 'Madrid', 'Madrid'), array('ES', 'Malaga', 'Malaga'), array('ES', 'Melilla', 'Melilla'),
					array('ES', 'Murcia', 'Murcia'), array('ES', 'Navarra', 'Navarra'), array('ES', 'Ourense', 'Ourense'),
					array('ES', 'Palencia', 'Palencia'), array('ES', 'Pontevedra', 'Pontevedra'), array('ES', 'Salamanca', 'Salamanca'),
					array('ES', 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife'), array('ES', 'Segovia', 'Segovia'),
					array('ES', 'Sevilla', 'Sevilla'), array('ES', 'Soria', 'Soria'), array('ES', 'Tarragona', 'Tarragona'),
					array('ES', 'Teruel', 'Teruel'), array('ES', 'Toledo', 'Toledo'), array('ES', 'Valencia', 'Valencia'),
					array('ES', 'Valladolid', 'Valladolid'), array('ES', 'Vizcaya', 'Vizcaya'), array('ES', 'Zamora', 'Zamora'),
					array('ES', 'Zaragoza', 'Zaragoza'), 
					
					array('FR', 1, 'Ain'), array('FR', 2, 'Aisne'), array('FR', 3, 'Allier'),
					array('FR', 4, 'Alpes-de-Haute-Provence'), array('FR', 5, 'Hautes-Alpes'), array('FR', 6, 'Alpes-Maritimes'),
					array('FR', 7, 'Ardèche'), array('FR', 8, 'Ardennes'), array('FR', 9, 'Ariège'), array('FR', 10, 'Aube'),
					array('FR', 11, 'Aude'), array('FR', 12, 'Aveyron'), array('FR', 13, 'Bouches-du-Rhône'),
					array('FR', 14, 'Calvados'), array('FR', 15, 'Cantal'), array('FR', 16, 'Charente'),
					array('FR', 17, 'Charente-Maritime'), array('FR', 18, 'Cher'), array('FR', 19, 'Corrèze'),
					array('FR', '2A', 'Corse-du-Sud'), array('FR', '2B', 'Haute-Corse'), array('FR', 21, 'Côte-d\'Or'),
					array('FR', 22, 'Côtes-d\'Armor'), array('FR', 23, 'Creuse'), array('FR', 24, 'Dordogne'), array('FR', 25, 'Doubs'),
					array('FR', 26, 'Drôme'), array('FR', 27, 'Eure'), array('FR', 28, 'Eure-et-Loir'), array('FR', 29, 'Finistère'),
					array('FR', 30, 'Gard'), array('FR', 31, 'Haute-Garonne'), array('FR', 32, 'Gers'), array('FR', 33, 'Gironde'),
					array('FR', 34, 'Hérault'), array('FR', 35, 'Ille-et-Vilaine'), array('FR', 36, 'Indre'),
					array('FR', 37, 'Indre-et-Loire'), array('FR', 38, 'Isère'), array('FR', 39, 'Jura'), array('FR', 40, 'Landes'),
					array('FR', 41, 'Loir-et-Cher'), array('FR', 42, 'Loire'), array('FR', 43, 'Haute-Loire'),
					array('FR', 44, 'Loire-Atlantique'), array('FR', 45, 'Loiret'), array('FR', 46, 'Lot'),
					array('FR', 47, 'Lot-et-Garonne'), array('FR', 48, 'Lozère'), array('FR', 49, 'Maine-et-Loire'),
					array('FR', 50, 'Manche'), array('FR', 51, 'Marne'), array('FR', 52, 'Haute-Marne'), array('FR', 53, 'Mayenne'),
					array('FR', 54, 'Meurthe-et-Moselle'), array('FR', 55, 'Meuse'), array('FR', 56, 'Morbihan'),
					array('FR', 57, 'Moselle'), array('FR', 58, 'Nièvre'), array('FR', 59, 'Nord'), array('FR', 60, 'Oise'),
					array('FR', 61, 'Orne'), array('FR', 62, 'Pas-de-Calais'), array('FR', 63, 'Puy-de-Dôme'),
					array('FR', 64, 'Pyrénées-Atlantiques'), array('FR', 65, 'Hautes-Pyrénées'), array('FR', 66, 'Pyrénées-Orientales'),
					array('FR', 67, 'Bas-Rhin'), array('FR', 68, 'Haut-Rhin'), array('FR', 69, 'Rhône'), array('FR', 70, 'Haute-Saône'),
					array('FR', 71, 'Saône-et-Loire'), array('FR', 72, 'Sarthe'), array('FR', 73, 'Savoie'),
					array('FR', 74, 'Haute-Savoie'), array('FR', 75, 'Paris'), array('FR', 76, 'Seine-Maritime'),
					array('FR', 77, 'Seine-et-Marne'), array('FR', 78, 'Yvelines'), array('FR', 79, 'Deux-Sèvres'),
					array('FR', 80, 'Somme'), array('FR', 81, 'Tarn'), array('FR', 82, 'Tarn-et-Garonne'), array('FR', 83, 'Var'),
					array('FR', 84, 'Vaucluse'), array('FR', 85, 'Vendée'), array('FR', 86, 'Vienne'), array('FR', 87, 'Haute-Vienne'),
					array('FR', 88, 'Vosges'), array('FR', 89, 'Yonne'), array('FR', 90, 'Territoire-de-Belfort'),
					array('FR', 91, 'Essonne'), array('FR', 92, 'Hauts-de-Seine'), array('FR', 93, 'Seine-Saint-Denis'),
					array('FR', 94, 'Val-de-Marne'), array('FR', 95, 'Val-d\'Oise'),

					array('RO', 'AB', 'Alba'),
					array('RO', 'AR', 'Arad'), array('RO', 'AG', 'Argeş'), array('RO', 'BC', 'Bacău'), array('RO', 'BH', 'Bihor'),
					array('RO', 'BN', 'Bistriţa-Năsăud'), array('RO', 'BT', 'Botoşani'), array('RO', 'BV', 'Braşov'),
					array('RO', 'BR', 'Brăila'), array('RO', 'B', 'Bucureşti'), array('RO', 'BZ', 'Buzău'),
					array('RO', 'CS', 'Caraş-Severin'), array('RO', 'CL', 'Călăraşi'), array('RO', 'CJ', 'Cluj'),
					array('RO', 'CT', 'Constanţa'), array('RO', 'CV', 'Covasna'), array('RO', 'DB', 'Dâmboviţa'),
					array('RO', 'DJ', 'Dolj'), array('RO', 'GL', 'Galaţi'), array('RO', 'GR', 'Giurgiu'), array('RO', 'GJ', 'Gorj'),
					array('RO', 'HR', 'Harghita'), array('RO', 'HD', 'Hunedoara'), array('RO', 'IL', 'Ialomiţa'),
					array('RO', 'IS', 'Iaşi'), array('RO', 'IF', 'Ilfov'), array('RO', 'MM', 'Maramureş'),
					array('RO', 'MH', 'Mehedinţi'), array('RO', 'MS', 'Mureş'), array('RO', 'NT', 'Neamţ'), array('RO', 'OT', 'Olt'),
					array('RO', 'PH', 'Prahova'), array('RO', 'SM', 'Satu-Mare'), array('RO', 'SJ', 'Sălaj'),
					array('RO', 'SB', 'Sibiu'), array('RO', 'SV', 'Suceava'), array('RO', 'TR', 'Teleorman'),
					array('RO', 'TM', 'Timiş'), array('RO', 'TL', 'Tulcea'), array('RO', 'VS', 'Vaslui'),
					array('RO', 'VL', 'Vâlcea'), array('RO', 'VN', 'Vrancea'),

					array('FI', 'Lappi', 'Lappi'),
					array('FI', 'Pohjois-Pohjanmaa', 'Pohjois-Pohjanmaa'), array('FI', 'Kainuu', 'Kainuu'),
					array('FI', 'Pohjois-Karjala', 'Pohjois-Karjala'), array('FI', 'Pohjois-Savo', 'Pohjois-Savo'),
					array('FI', 'Etelä-Savo', 'Etelä-Savo'), array('FI', 'Etelä-Pohjanmaa', 'Etelä-Pohjanmaa'),
					array('FI', 'Pohjanmaa', 'Pohjanmaa'), array('FI', 'Pirkanmaa', 'Pirkanmaa'), array('FI', 'Satakunta', 'Satakunta'),
					array('FI', 'Keski-Pohjanmaa', 'Keski-Pohjanmaa'), array('FI', 'Keski-Suomi', 'Keski-Suomi'),
					array('FI', 'Varsinais-Suomi', 'Varsinais-Suomi'), array('FI', 'Etelä-Karjala', 'Etelä-Karjala'),
					array('FI', 'Päijät-Häme', 'Päijät-Häme'), array('FI', 'Kanta-Häme', 'Kanta-Häme'),
					array('FI', 'Uusimaa', 'Uusimaa'), array('FI', 'Itä-Uusimaa', 'Itä-Uusimaa'),
					array('FI', 'Kymenlaakso', 'Kymenlaakso'), array('FI', 'Ahvenanmaa', 'Ahvenanmaa'),
					
					array('EE', 'EE-37', 'Harjumaa'), array('EE', 'EE-39', 'Hiiumaa'), array('EE', 'EE-44', 'Ida-Virumaa'),
					array('EE', 'EE-49', 'Jõgevamaa'), array('EE', 'EE-51', 'Järvamaa'), array('EE', 'EE-57', 'Läänemaa'),
					array('EE', 'EE-59', 'Lääne-Virumaa'), array('EE', 'EE-65', 'Põlvamaa'), array('EE', 'EE-67', 'Pärnumaa'),
					array('EE', 'EE-70', 'Raplamaa'), array('EE', 'EE-74', 'Saaremaa'), array('EE', 'EE-78', 'Tartumaa'),
					array('EE', 'EE-82', 'Valgamaa'), array('EE', 'EE-84', 'Viljandimaa'), array('EE', 'EE-86', 'Võrumaa'),
					
					array('LV', 'LV-DGV', 'Daugavpils'), array('LV', 'LV-JEL', 'Jelgava'), array('LV', 'Jēkabpils', 'Jēkabpils'),
					array('LV', 'LV-JUR', 'Jūrmala'), array('LV', 'LV-LPX', 'Liepāja'), array('LV', 'LV-LE', 'Liepājas novads'),
					array('LV', 'LV-REZ', 'Rēzekne'), array('LV', 'LV-RIX', 'Rīga'), array('LV', 'LV-RI', 'Rīgas novads'),
					array('LV', 'Valmiera', 'Valmiera'), array('LV', 'LV-VEN', 'Ventspils'),
					array('LV', 'Aglonas novads', 'Aglonas novads'), array('LV', 'LV-AI', 'Aizkraukles novads'),
					array('LV', 'Aizputes novads', 'Aizputes novads'), array('LV', 'Aknīstes novads', 'Aknīstes novads'),
					array('LV', 'Alojas novads', 'Alojas novads'), array('LV', 'Alsungas novads', 'Alsungas novads'),
					array('LV', 'LV-AL', 'Alūksnes novads'), array('LV', 'Amatas novads', 'Amatas novads'),
					array('LV', 'Apes novads', 'Apes novads'), array('LV', 'Auces novads', 'Auces novads'),
					array('LV', 'Babītes novads', 'Babītes novads'), array('LV', 'Baldones novads', 'Baldones novads'),
					array('LV', 'Baltinavas novads', 'Baltinavas novads'), array('LV', 'LV-BL', 'Balvu novads'),
					array('LV', 'LV-BU', 'Bauskas novads'), array('LV', 'Beverīnas novads', 'Beverīnas novads'),
					array('LV', 'Brocēnu novads', 'Brocēnu novads'), array('LV', 'Burtnieku novads', 'Burtnieku novads'),
					array('LV', 'Carnikavas novads', 'Carnikavas novads'), array('LV', 'Cesvaines novads', 'Cesvaines novads'),
					array('LV', 'Ciblas novads', 'Ciblas novads'), array('LV', 'LV-CE', 'Cēsu novads'),
					array('LV', 'Dagdas novads', 'Dagdas novads'), array('LV', 'LV-DA', 'Daugavpils novads'),
					array('LV', 'LV-DO', 'Dobeles novads'), array('LV', 'Dundagas novads', 'Dundagas novads'),
					array('LV', 'Durbes novads', 'Durbes novads'), array('LV', 'Engures novads', 'Engures novads'),
					array('LV', 'Garkalnes novads', 'Garkalnes novads'), array('LV', 'Grobiņas novads', 'Grobiņas novads'),
					array('LV', 'LV-GU', 'Gulbenes novads'), array('LV', 'Iecavas novads', 'Iecavas novads'),
					array('LV', 'Ikšķiles novads', 'Ikšķiles novads'), array('LV', 'Ilūkstes novads', 'Ilūkstes novads'),
					array('LV', 'Inčukalna novads', 'Inčukalna novads'), array('LV', 'Jaunjelgavas novads', 'Jaunjelgavas novads'),
					array('LV', 'Jaunpiebalgas novads', 'Jaunpiebalgas novads'), array('LV', 'Jaunpils novads', 'Jaunpils novads'),
					array('LV', 'LV-JL', 'Jelgavas novads'), array('LV', 'LV-JK', 'Jēkabpils novads'),
					array('LV', 'Kandavas novads', 'Kandavas novads'), array('LV', 'Kokneses novads', 'Kokneses novads'),
					array('LV', 'Krimuldas novads', 'Krimuldas novads'), array('LV', 'Krustpils novads', 'Krustpils novads'),
					array('LV', 'LV-KR', 'Krāslavas novads'), array('LV', 'LV-KU', 'Kuldīgas novads'),
					array('LV', 'Kārsavas novads', 'Kārsavas novads'), array('LV', 'Lielvārdes novads', 'Lielvārdes novads'),
					array('LV', 'LV-LM', 'Limbažu novads'), array('LV', 'Lubānas novads', 'Lubānas novads'),
					array('LV', 'LV-LU', 'Ludzas novads'), array('LV', 'Līgatnes novads', 'Līgatnes novads'),
					array('LV', 'Līvānu novads', 'Līvānu novads'), array('LV', 'LV-MA', 'Madonas novads'),
					array('LV', 'Mazsalacas novads', 'Mazsalacas novads'), array('LV', 'Mālpils novads', 'Mālpils novads'),
					array('LV', 'Mārupes novads', 'Mārupes novads'), array('LV', 'Naukšēnu novads', 'Naukšēnu novads'),
					array('LV', 'Neretas novads', 'Neretas novads'), array('LV', 'Nīcas novads', 'Nīcas novads'),
					array('LV', 'LV-OG', 'Ogres novads'), array('LV', 'Olaines novads', 'Olaines novads'),
					array('LV', 'Ozolnieku novads', 'Ozolnieku novads'), array('LV', 'LV-PR', 'Preiļu novads'),
					array('LV', 'Priekules novads', 'Priekules novads'), array('LV', 'Priekuļu novads', 'Priekuļu novads'),
					array('LV', 'Pārgaujas novads', 'Pārgaujas novads'), array('LV', 'Pāvilostas novads', 'Pāvilostas novads'),
					array('LV', 'Pļaviņu novads', 'Pļaviņu novads'), array('LV', 'Raunas novads', 'Raunas novads'),
					array('LV', 'Riebiņu novads', 'Riebiņu novads'), array('LV', 'Rojas novads', 'Rojas novads'),
					array('LV', 'Ropažu novads', 'Ropažu novads'), array('LV', 'Rucavas novads', 'Rucavas novads'),
					array('LV', 'Rugāju novads', 'Rugāju novads'), array('LV', 'Rundāles novads', 'Rundāles novads'),
					array('LV', 'LV-RE', 'Rēzeknes novads'), array('LV', 'Rūjienas novads', 'Rūjienas novads'),
					array('LV', 'Salacgrīvas novads', 'Salacgrīvas novads'), array('LV', 'Salas novads', 'Salas novads'),
					array('LV', 'Salaspils novads', 'Salaspils novads'), array('LV', 'LV-SA', 'Saldus novads'),
					array('LV', 'Saulkrastu novads', 'Saulkrastu novads'), array('LV', 'Siguldas novads', 'Siguldas novads'),
					array('LV', 'Skrundas novads', 'Skrundas novads'), array('LV', 'Skrīveru novads', 'Skrīveru novads'),
					array('LV', 'Smiltenes novads', 'Smiltenes novads'), array('LV', 'Stopiņu novads', 'Stopiņu novads'),
					array('LV', 'Strenču novads', 'Strenču novads'), array('LV', 'Sējas novads', 'Sējas novads'),
					array('LV', 'LV-TA', 'Talsu novads'), array('LV', 'LV-TU', 'Tukuma novads'),
					array('LV', 'Tērvetes novads', 'Tērvetes novads'), array('LV', 'Vaiņodes novads', 'Vaiņodes novads'),
					array('LV', 'LV-VK', 'Valkas novads'), array('LV', 'LV-VM', 'Valmieras novads'),
					array('LV', 'Varakļānu novads', 'Varakļānu novads'), array('LV', 'Vecpiebalgas novads', 'Vecpiebalgas novads'),
					array('LV', 'Vecumnieku novads', 'Vecumnieku novads'), array('LV', 'LV-VE', 'Ventspils novads'),
					array('LV', 'Viesītes novads', 'Viesītes novads'), array('LV', 'Viļakas novads', 'Viļakas novads'),
					array('LV', 'Viļānu novads', 'Viļānu novads'), array('LV', 'Vārkavas novads', 'Vārkavas novads'),
					array('LV', 'Zilupes novads', 'Zilupes novads'), array('LV', 'Ādažu novads', 'Ādažu novads'),
					array('LV', 'Ērgļu novads', 'Ērgļu novads'), array('LV', 'Ķeguma novads', 'Ķeguma novads'),
					array('LV', 'Ķekavas novads', 'Ķekavas novads'), 
					
					array('LT', 'LT-AL', 'Alytaus Apskritis'),
					array('LT', 'LT-KU', 'Kauno Apskritis'), array('LT', 'LT-KL', 'Klaipėdos Apskritis'),
					array('LT', 'LT-MR', 'Marijampolės Apskritis'), array('LT', 'LT-PN', 'Panevėžio Apskritis'),
					array('LT', 'LT-SA', 'Šiaulių Apskritis'), array('LT', 'LT-TA', 'Tauragės Apskritis'),
					array('LT', 'LT-TE', 'Telšių Apskritis'), array('LT', 'LT-UT', 'Utenos Apskritis'),
					array('LT', 'LT-VL', 'Vilniaus Apskritis')
		
		];
		return $data;
	
	}
	
	
	
	
	
	
	
	
	
	
	
}