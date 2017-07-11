<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\helper;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Country
{
    /**废弃    
     * 快捷支付，得到省的html
     */
    public static function getExpressPaymentStateHtml($countryCode, $stateCode = '', $ischangestate = '')
    {
        $state = Yii::$service->helper->country->getStateOptionsByContryCode($countryCode, $stateCode);

        $stateHtml = '<label class="required" for="shipping:region_id"><em>*</em>State</label>';
        $stateHtml .= '<div class="input-box">';
        if ($state) {
            $stateHtml .= '<select class="selectstate required-entry">';
            $stateHtml .= '<option value=""> Please select region, state or province</option>';
            $stateHtml .= $state;
            $stateHtml .= '</select>';
            if ((int) $ischangestate == 1) {
                $stateHtml .= '<input style="display:none"  value=""  type="text" class="input-text  required-entry validation-passed inputstate" title="State"  name="shipping[state]" id="shipping:state" />';
            } else {
                $stateHtml .= '<input style="display:none"  value="'.($stateCode ? $stateCode : '').'"  type="text" class="input-text  required-entry validation-passed inputstate" title="State"  name="shipping[state]" id="shipping:state" />';
            }
            $stateHtml .= '';
        } else {
            if ((int) $ischangestate == 1) {
                $stateHtml .= '<input  value=""  type="text" class="input-text  required-entry validation-passed inputstate" title="State"  name="shipping[state]" id="shipping:state" />';
            } else {
                $stateHtml .= '<input  value="'.($stateCode ? $stateCode : '').'"  type="text" class="input-text  required-entry validation-passed inputstate" title="State"  name="shipping[state]" id="shipping:state" />';
            }
        }
        $stateHtml .= '</div>';

        return $stateHtml;
    }

    /**废弃    
     * 快捷支付，得到省的html
     */
    public static function getStandPaymentStateHtml($countryCode, $stateCode = '', $isajaxchange = '')
    {
        $state = Yii::$service->helper->country->getStateOptionsByContryCode($countryCode, $stateCode);

        $stateHtml = '<label for="billing:state" class="required">State<span class="required">*</span></label>';
        if ($state) {
            $stateHtml .= '<select class="selectstate">';
            $stateHtml .= '<option value="">Please select region, state or province</option>';
            $stateHtml .= $state;
            $stateHtml .= '</select>';
            if ($isajaxchange) {
                $stateHtml .= '<input style="display:none;"  value=""  type="text" class="required-entry input-text inputstate" title="State"  name="billing[state]" id="billing:state" />';
            } else {
                $stateHtml .= '<input style="display:none;"  value="'.($stateCode ? $stateCode : '').'"  type="text" class="required-entry input-text inputstate" title="State"  name="billing[state]" id="billing:state" />';
            }
            $stateHtml .= '';
        } else {
            if ($isajaxchange) {
                $stateHtml .= '<input  value=""  type="text" class="required-entry input-text inputstate" title="State"  name="billing[state]" id="billing:state" />';
            } else {
                $stateHtml .= '<input  value="'.($stateCode ? $stateCode : '').'"  type="text" class="required-entry input-text inputstate" title="State"  name="billing[state]" id="billing:state" />';
            }
        }

        return $stateHtml;
    }
}
