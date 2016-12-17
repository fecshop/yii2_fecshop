<div class="main container one-column">
	<div class="col-main">
								 <script>
	jQuery(document).ready(function(){
		jQuery("#onestepcheckout-coupon-add").click(function(){
			couponcode = jQuery("#id_couponcode").val();
			if(!couponcode){
				alert('coupon code can not empty');
			}else{
				//alert(couponcode);
				jQuery(".coupon_code").val(couponcode);
				jQuery(".coupon_code_form").submit();
			}
		});
	});
</script>

<form class="coupon_code_form" action="http://www.intosmile.com/checkout/onepage/coupon" method="post">
	<input class="thiscsrf" value="dTJOYlNrbGktV3s0PgxYBSBELw5hCRoYO1EdGzwuQSA8ShEyKwInXQ==" name="_csrf" type="hidden">	<input name="coupon_code" class="coupon_code" type="hidden">
</form>
	
<form action="http://www.intosmile.com/checkout/onepage" method="post" id="onestepcheckout-form">
	<input class="thiscsrf" value="dTJOYlNrbGktV3s0PgxYBSBELw5hCRoYO1EdGzwuQSA8ShEyKwInXQ==" name="_csrf" type="hidden">	<fieldset style="margin: 0;" class="group-select">

		<h1 class="onestepcheckout-title">Checkout</h1>
		<p class="onestepcheckout-description">Welcome to the checkout. Fill in the fields below to complete your purchase!</p>
		<p class="onestepcheckout-login-link">
			<a href="http://www.intosmile.com/customer/account/login/" id="onestepcheckout-login-link">Already registered? Click here to login.</a>
		</p>

		<div class="onestepcheckout-threecolumns checkoutcontainer onestepcheckout-skin-generic onestepcheckout-enterprise">
			<div class="onestepcheckout-column-left">
				<div id="billing_address">
				
					<ul>
						<li>
							<p class="onestepcheckout-numbers onestepcheckout-numbers-1">Billing address</p>
						</li>
						<li>
							<div>
								<ul id="billing_address_list">			
					<li class="clearfix">
						<div class="input-box input-firstname">
							<label for="billing:firstname">First Name<span class="required">*</span></label>
							<input value="" id="billing:firstname" name="billing[firstname]" class="required-entry input-text" type="text">
						</div>
						<div class="input-box input-lastname">
							<label for="billing:lastname">Last Name <span class="required">*</span></label>
							<input value="" id="billing:lastname" name="billing[lastname]" class="required-entry input-text" type="text">
						</div>
					</li>
					<li class="clearfix">
						<div style="width:100%;" class="  input-box input-email">
							<label for="billing:email">Email Address <span class="required">*</span></label>
							<input style="width:83%;" value="" class="validate-email required-entry input-text" title="Email Address" id="billing:email" name="billing[email]" type="text">
							<div class="customer_email_validation">
							
							</div>
						</div>
					</li>
					<li>
						<div style="width:100%;" class="input-box input-telephone">
							<label for="billing:telephone">Telephone <span class="required">*</span></label>
							<input style="width:83%;" value="" id="billing:telephone" class="required-entry input-text" title="Telephone" name="billing[telephone]" type="text">
						</div>
					</li>
					<li class="clearfix">
						<div class="input-box input-address">
							<label for="billing:street1">Address<span class="required">*</span></label>
							<input value="" class="required-entry input-text onestepcheckout-address-line" id="billing:street1" name="billing[street1]" title="Street Address 1" type="text">
							<br>
							<input value="" class="input-text onestepcheckout-address-line" id="billing:street2" name="billing[street2]" title="Street Address 2" type="text">
						</div>
					</li>
					<li class="clearfix">
						<div class="input-box input-country">
							<label for="billing:country">Country <span class="required">*</span></label>
								<select title="Country" class="billing_country validate-select" id="billing:country" name="billing[country]"><option value="AF">Afghanistan</option><option value="AX">Åland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="VG">British Virgin Islands</option><option value="BN">Brunei</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos [Keeling] Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo - Brazzaville</option><option value="CD">Congo - Kinshasa</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">C?te d¡¯Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island and McDonald Islands</option><option value="HN">Honduras</option><option value="HK">Hong Kong SAR China</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Laos</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macau SAR China</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar [Burma]</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="AN">Netherlands Antilles</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="MP">Northern Mariana Islands</option><option value="KP">North Korea</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestinian Territories</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn Islands</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">R¨¦union</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="BL">Saint Barth¨¦lemy</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="MF">Saint Martin</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">S?o Tom¨¦ and Pr¨ªncipe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia and the South Sandwich Islands</option><option value="KR">South Korea</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syria</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option selected="selected" value="US">United States</option><option value="UY">Uruguay</option><option value="UM">U.S. Minor Outlying Islands</option><option value="VI">U.S. Virgin Islands</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VA">Vatican City</option><option value="VE">Venezuela</option><option value="VN">Vietnam</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select>
							</div>
					</li>
					<li class="clearfix">
						<div class="input-box input-city">
							<label for="billing:city">City <span class="required">*</span></label>
							<input value="" id="billing:city" class="required-entry input-text" title="City" name="billing[city]" type="text">
						</div>
					</li>
					<li class="clearfix">
						<div class="input-box input-zip">
							<label for="billing:zip">Zip Code <span class="required">*</span></label>
							<input value="" class="validate-zip-international required-entry input-text" id="billing:zip" name="billing[zip]" title="Zip Code" type="text">
						</div>
						<div class="input-box input-state">
							<label for="billing:state" class="required">State <span class="required">*</span></label><select class="selectstate"><option value="">Please select region, state or province</option><option value="AL" rel="Alabama">Alabama</option><option value="AK" rel="Alaska">Alaska</option><option value="AS" rel="American Samoa">American Samoa</option><option value="AZ" rel="Arizona">Arizona</option><option value="AR" rel="Arkansas">Arkansas</option><option value="AF" rel="Armed Forces Africa">Armed Forces Africa</option><option value="AA" rel="Armed Forces Americas">Armed Forces Americas</option><option value="AC" rel="Armed Forces Canada">Armed Forces Canada</option><option value="AE" rel="Armed Forces Europe">Armed Forces Europe</option><option value="AM" rel="Armed Forces Middle East">Armed Forces Middle East</option><option value="AP" rel="Armed Forces Pacific">Armed Forces Pacific</option><option value="CA" rel="California">California</option><option value="CO" rel="Colorado">Colorado</option><option value="CT" rel="Connecticut">Connecticut</option><option value="DE" rel="Delaware">Delaware</option><option value="DC" rel="District of Columbia">District of Columbia</option><option value="FM" rel="Federated States Of Micronesia">Federated States Of Micronesia</option><option value="FL" rel="Florida">Florida</option><option value="GA" rel="Georgia">Georgia</option><option value="GU" rel="Guam">Guam</option><option value="HI" rel="Hawaii">Hawaii</option><option value="ID" rel="Idaho">Idaho</option><option value="IL" rel="Illinois">Illinois</option><option value="IN" rel="Indiana">Indiana</option><option value="IA" rel="Iowa">Iowa</option><option value="KS" rel="Kansas">Kansas</option><option value="KY" rel="Kentucky">Kentucky</option><option value="LA" rel="Louisiana">Louisiana</option><option value="ME" rel="Maine">Maine</option><option value="MH" rel="Marshall Islands">Marshall Islands</option><option value="MD" rel="Maryland">Maryland</option><option value="MA" rel="Massachusetts">Massachusetts</option><option value="MI" rel="Michigan">Michigan</option><option value="MN" rel="Minnesota">Minnesota</option><option value="MS" rel="Mississippi">Mississippi</option><option value="MO" rel="Missouri">Missouri</option><option value="MT" rel="Montana">Montana</option><option value="NE" rel="Nebraska">Nebraska</option><option value="NV" rel="Nevada">Nevada</option><option value="NH" rel="New Hampshire">New Hampshire</option><option value="NJ" rel="New Jersey">New Jersey</option><option value="NM" rel="New Mexico">New Mexico</option><option value="NY" rel="New York">New York</option><option value="NC" rel="North Carolina">North Carolina</option><option value="ND" rel="North Dakota">North Dakota</option><option value="MP" rel="Northern Mariana Islands">Northern Mariana Islands</option><option value="OH" rel="Ohio">Ohio</option><option value="OK" rel="Oklahoma">Oklahoma</option><option value="OR" rel="Oregon">Oregon</option><option value="PW" rel="Palau">Palau</option><option value="PA" rel="Pennsylvania">Pennsylvania</option><option value="PR" rel="Puerto Rico">Puerto Rico</option><option value="RI" rel="Rhode Island">Rhode Island</option><option value="SC" rel="South Carolina">South Carolina</option><option value="SD" rel="South Dakota">South Dakota</option><option value="TN" rel="Tennessee">Tennessee</option><option value="TX" rel="Texas">Texas</option><option value="UT" rel="Utah">Utah</option><option value="VT" rel="Vermont">Vermont</option><option value="VI" rel="Virgin Islands">Virgin Islands</option><option value="VA" rel="Virginia">Virginia</option><option value="WA" rel="Washington">Washington</option><option value="WV" rel="West Virginia">West Virginia</option><option value="WI" rel="Wisconsin">Wisconsin</option><option value="WY" rel="Wyoming">Wyoming</option></select><input style="display:none;" value="" class="required-entry input-text inputstate" title="State" name="billing[state]" id="billing:state" type="text">
						</div>
					</li>
					<li class="clearfix">
							<div class="input-box">
								<input value="1" name="create_account" id="id_create_account" type="checkbox">
								<label for="id_create_account">Create an account for later use</label>
							</div>
						</li>
							
						<li style="display: none;" id="onestepcheckout-li-password">
							<div class="input-box input-password">
								<label for="billing:customer_password">Password</label><br>
								<input name="billing[customer_password]" id="billing:customer_password" title="Password" value="" class="validate-password input-text" type="password">
							</div>
							<div class="input-box input-password">
								<label for="billing:confirm_password">Confirm password</label><br>
								<input name="billing[confirm_password]" title="Confirm Password" id="billing:confirm_password" value="" class="validate-password input-text" type="password">
							</div>
						</li>                
					<script>
					jQuery(document).ready(function(){
						jQuery("#id_create_account").click(function(){
							if(jQuery(this).attr("checked")){
								jQuery("#onestepcheckout-li-password").show();
								jQuery("#onestepcheckout-li-password input").addClass("required-entry");
							}else{
								jQuery("#onestepcheckout-li-password").hide();
								jQuery("#onestepcheckout-li-password input").removeClass("required-entry");
							}
						});
					});
					</script>
				      
				</ul>							</div>
						</li>
						<li>
							<input value="1" id="billing:use_for_shipping_yes" name="billing[use_for_shipping]" type="hidden">
						</li>
					</ul>
				</div>
			</div>

			<div class="onestepcheckout-column-middle">
				<div class="onestepcheckout-shipping-method">
					<p class="onestepcheckout-numbers onestepcheckout-numbers-2">Shipping method</p>
					<div class="onestepcheckout-shipping-method-block">    
						<dl class="shipment-methods"><div class="shippingmethods">
							<dd class="flatrate">Free shipping( 7-20 work days)</dd>
							<dt>
								<input data-role="none" checked="checked" id="s_method_flatrate_flatrate1" value="free_shipping" class="validate-one-required-by-name" name="shipping_method" type="radio">
								<label for="s_method_flatrate_flatrate1">HKBRAM
									<strong>                 
										<span class="price">$0.00</span>
									</strong>
								</label>
							</dt>
						</div><div class="shippingmethods">
							<dd class="flatrate">Fast Shipping( 5-10 work days)</dd>
							<dt>
								<input data-role="none" id="s_method_flatrate_flatrate2" value="fast_shipping" class="validate-one-required-by-name" name="shipping_method" type="radio">
								<label for="s_method_flatrate_flatrate2">HKDHL
									<strong>                 
										<span class="price">$31.90</span>
									</strong>
								</label>
							</dt>
						</div></dl>
					</div>				</div>
				<p class="onestepcheckout-numbers onestepcheckout-numbers-3">Payment method</p>
				
				<div class="payment-methods">
					<dl id="checkout-payment-method-load">
						<dt>
							<span class="no-display"><input checked="checked" name="payment[method]" value="paypal_standard" id="p_method_paypal_standard" type="radio"></span>
							<label for="p_method_paypal_standard">
								<p class="payment"></p>
								<div class="clear"></div>
							</label>
						</dt>
						<dd class="payment-method" id="container_payment_method_paypal_standard">
							<ul style="" id="payment_form_paypal_standard" class="form-list">
								<li class="form-alt"></li>
							</ul>
						</dd>
					</dl>
				</div>				
					
				<div class="onestepcheckout-coupons">
					<div style="display: none;" id="coupon-notice"></div>
					<div class="op_block_title">Coupon codes (optional)</div>
					<label for="id_couponcode">Enter your coupon code if you have one.</label>
					
					<input value="" id="id_couponcode" name="onestepcheckout-couponcode" class="input-text" type="text">
					<br>
					<button style="" type="button" class="submitbutton form-button-alt" id="onestepcheckout-coupon-add">Apply Coupon</button>
				</div>			</div>

			<div class="onestepcheckout-column-right">
				<p class="onestepcheckout-numbers onestepcheckout-numbers-4">Review your order</p>
				<div class="onestepcheckout-summary">
					<table class="onestepcheckout-summary">
						<thead>
							<tr>
								<th class="name">Product</th>
								<th class="qty">Qty</th>
								<th class="total">Subtotal</th>
							</tr>
						</thead>
						<tbody>
							<tr>
						<td class="name"><a target="_blank" href="http://www.intosmile.com/classic-style-solid-color-lace-up-round-toe-flat-boots-for-women.html">Classic Style Solid Color Lace-up Round Toe Flat Boots For Women</a></td>
						<td class="qty">1</td>
						<td class="total"><span class="price">$35.00</span></td>
					</tr>						</tbody>
					</table>

					<table class="onestepcheckout-totals">
						<tbody>
							<tr>
								<td class="title">Subtotal</td>
								<td class="value">
									<span class="price">$35.00</span>       
								</td>
							</tr>
							<tr>
								<td class="title">Shipping</td>
								<td class="value">
									<span class="price">$0.00</span> 
								</td>
							</tr>
							<tr>
								<td class="title">Discount</td>
								<td class="value">
									<span class="price">-$0.00</span> 
								</td>
							</tr>
							<tr class="grand-total">
								<td class="title">Grand total</td>
								<td class="value">
									<span class="price">$35.00</span>   
								</td>
							</tr>						</tbody>
					</table>
				</div>
				<div class="onestepcheckout-place-order">
					<span><img src="http://www.intosmile.com/skin/default/images/scroll/waitPage.gif"></span>
					<a class="large orange onestepcheckout-button" href="javascript:void(0)" id="onestepcheckout-place-order">Place order now</a>
				</div>
			</div>
			<div style="clear: both;">&nbsp;</div>
		</div>
	</fieldset>
</form>
	
<script>
	function ajaxreflush(){
		shipping_method = jQuery("input[name=shipping_method]:checked").val();
		//alert(shipping_method);
		country = jQuery(".billing_country").val();
		address_id = jQuery(".address_list").val();
		jQuery(".onestepcheckout-summary").html('&lt;div style="text-align:center;min-height:40px;"&gt;&lt;img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /&gt;&lt;/div&gt;');
		ajaxurl = "http://www.intosmile.com/checkout/onepage/getshipping";
		state   = jQuery(".inputstate").val();
		jQuery.ajax({
			async:false,
			timeout: 8000,
			dataType: 'json', 
			type:'get',
			data: {
					'country':country,
					'shipping_method':shipping_method,
					'address_id':address_id,
					'state':state,
					},
			url:ajaxurl,
			success:function(data, textStatus){ 
				
				jQuery(".onestepcheckout-summary").html(data.total_html)
				jQuery(".input-state").html(data.state);
					
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){
					
			}
		});
	}	
	jQuery(document).ready(function(){
		
		jQuery("#onestepcheckout-place-order").click(function(){
			jQuery(".validation-advice").remove();
			i = 0;
			address_list = jQuery(".address_list").val();
			if(address_list){
				jQuery(".onestepcheckout-place-order span").show();
				jQuery("#onestepcheckout-form").submit();
			}else{
				jQuery("#onestepcheckout-form .required-entry").each(function(){
					value = jQuery(this).val();
					if(!value){
						i++;
						jQuery(this).after('&lt;div style=""  class="validation-advice"&gt;This is a required field.&lt;/div&gt;');
					}
				});
				if(!i){
					jQuery(".onestepcheckout-place-order span").show();
					jQuery("#onestepcheckout-form").submit();
				}
			}
		});
		
		jQuery(".address_list").change(function(){
			val = jQuery(this).val();
			if(!val){
				jQuery(".billing_address_list_new").show();
				 
				jQuery(".save_in_address_book").attr("checked","checked");
				ajaxreflush();
				
			}else{
				jQuery(".billing_address_list_new").hide();
				jQuery(".save_in_address_book").attr("checked",false);
				addressid = jQuery(this).val();
				
				if(addressid){
					
					jQuery(".onestepcheckout-shipping-method-block").html('&lt;div style="text-align:center;min-height:40px;"&gt;&lt;img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /&gt;&lt;/div&gt;');
					jQuery(".onestepcheckout-summary").html('&lt;div style="text-align:center;min-height:40px;"&gt;&lt;img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /&gt;&lt;/div&gt;');
					shipping_method = jQuery("input[name=shipping_method]:checked").val();
					ajaxurl = "http://www.intosmile.com/checkout/onepage/changeaddress";
					jQuery.ajax({
						async:false,
						timeout: 8000,
						dataType: 'json', 
						type:'get',
						data: {
								'address_id':addressid,
								'shipping_method':shipping_method
								},
						url:ajaxurl,
						success:function(data, textStatus){ 
							
							jQuery(".onestepcheckout-shipping-method").html(data.shippint_html);
							jQuery(".onestepcheckout-summary").html(data.total_html)
							//jQuery(".onestepcheckout-summary tbody").html(data.product_html);
							//jQuery(".onestepcheckout-totals tbody").html(data.total_html);
								
						},
						error:function (XMLHttpRequest, textStatus, errorThrown){
								
						}
					});
				}
			}
		});
		
		//jQuery(document).on(".billing_country","click",function(){
		jQuery(".billing_country").change(function(){
			country = jQuery(this).val();
			state   = jQuery(".inputstate").val();
			shipping_method = jQuery("input[name=shipping_method]:checked").val();
			//alert(shipping_method);
			
			jQuery(".onestepcheckout-shipping-method-block").html('&lt;div style="text-align:center;min-height:40px;"&gt;&lt;img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /&gt;&lt;/div&gt;');
			jQuery(".onestepcheckout-summary").html('&lt;div style="text-align:center;min-height:40px;"&gt;&lt;img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /&gt;&lt;/div&gt;');
			ajaxurl = "http://www.intosmile.com/checkout/onepage/getshipping";
			jQuery.ajax({
				async:false,
				timeout: 8000,
				dataType: 'json', 
				type:'get',
				data: {
						'country':country,
						'shipping_method':shipping_method,
						'state':state
						},
				url:ajaxurl,
				success:function(data, textStatus){ 
					
					jQuery(".onestepcheckout-shipping-method").html(data.shippint_html);
					jQuery(".onestepcheckout-summary").html(data.total_html);
					jQuery(".input-state").html(data.state);
					//jQuery(".onestepcheckout-summary tbody").html(data.product_html);
					//jQuery(".onestepcheckout-totals tbody").html(data.total_html);
						
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){
						
				}
			});
				
		});
		
	
		jQuery(".onestepcheckout-column-middle").off("click").on("click","input[name=shipping_method]",function(){
			ajaxreflush();
			
		});
		
		
		
		jQuery("#billing_address_list").off("change").on("change",".selectstate",function(){
			value = $(".selectstate option:selected").text();
			if($(".selectstate").val()){
				jQuery(".inputstate").val(value);
			}else{
				jQuery(".inputstate").val('');
			}
		});
		
		
		

	});	
		
	
</script>
    

							</div>
</div>