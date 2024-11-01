<?php

$wc_main_settings = [];
$wc_main_settings = get_option('woocommerce_servientrega_shipping_settings');

if(isset($_POST['servientrega_license']))
{
    if (!wp_verify_nonce( $_POST['_wpnonce'], 'woocommerce-settings' ))
        return;

    $license = $_POST['servientrega_license'];
    Shipping_Servientrega_WC::upgrade_working_plugin($license);
    header("Refresh:0");
}

$label_license = __('Licencia');
$data_tip = __('La licencia que se adquirió para el uso del plugin completo');

$servientrega_license = isset($wc_main_settings['servientrega_license']) ? $wc_main_settings['servientrega_license'] : '';

$htmlLicense = <<<HTML
<table>
    <tr valign="top">
         <td style="width:25%;padding-top:40px;font-weight:bold;">
            <label for="servientrega_license">$label_license</label><span class="woocommerce-help-tip" data-tip="$data_tip"></span>
         </td>
         <td scope="row" class="titledesc" style="display:block;margin-bottom:20px;margin-top:3px;padding-top:40px;">
            <fieldset style="padding:3px;">
                <input id="servientrega_license" name="servientrega_license" type="password" value="$servientrega_license">
            </fieldset>
         </td>
    </tr>
</table>
HTML;
return $htmlLicense;
