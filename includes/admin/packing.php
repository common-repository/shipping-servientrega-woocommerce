<?php

$this->init_settings();

global $woocommerce;

$wc_main_settings = array();

if(isset($_POST['save']))
{

    if (!wp_verify_nonce( $_POST['_wpnonce'], 'woocommerce-settings' ))
        return;

    $wc_main_settings = get_option('woocommerce_servientrega_shipping_settings');

    if (isset($_POST['packing'])){
        $name_box_size = $_POST['packing']['name_box_size'];
        $wc_main_settings['packing']['name_box_size'] = shipping_servientrega_wc_ss_sanitize($name_box_size);
        $length_box_size = $_POST['packing']['length_box_size'];
        $wc_main_settings['packing']['length_box_size'] = shipping_servientrega_wc_ss_sanitize($length_box_size);
        $width_box_size = $_POST['packing']['width_box_size'];
        $wc_main_settings['packing']['width_box_size'] = shipping_servientrega_wc_ss_sanitize($width_box_size);
        $height_box_size = $_POST['packing']['height_box_size'];
        $wc_main_settings['packing']['height_box_size'] = shipping_servientrega_wc_ss_sanitize($height_box_size);
        $max_weight_box_size = $_POST['packing']['max_weight_box_size'];
        $wc_main_settings['packing']['max_weight_box_size'] = shipping_servientrega_wc_ss_sanitize($max_weight_box_size);

        $volumes = [];

        for ($i = 0; $i < count($_POST['packing']['name_box_size']); $i++ ){
            $volumes[] = $_POST['packing']['length_box_size'][$i] * $_POST['packing']['width_box_size'][$i] * $_POST['packing']['height_box_size'][$i];
        }

        $wc_main_settings['packing']['volumes'] = $volumes;
    }else{
        unset($wc_main_settings['packing']);
    }

    $wc_main_settings = array_merge($wc_main_settings);

    update_option('woocommerce_servientrega_shipping_settings',$wc_main_settings);

}

$general_settings = get_option('woocommerce_servientrega_shipping_settings');

$volume_product = 512;

$boxes = array_filter($general_settings['packing']['volumes'], function ($v, $k) use($volume_product){
    return $volume_product <= $v;
}, ARRAY_FILTER_USE_BOTH);

var_dump($boxes);
function shipping_servientrega_wc_ss_sanitize($rate){

    $result = [];

    foreach ($rate as $key => $val){
        $result[$key] = sanitize_text_field($val);
    }

    return $result;

}


$rows = <<<HTML
<tr>
    <td>
        <input type="checkbox">
    </td>
    <td>
        <input type="text" name="packing[name_box_size][]" required>
    </td>
    <td>
        <input type="text" name="packing[length_box_size][]" class="wc_input_price" required>
    </td>
    <td>
        <input type="text" name="packing[width_box_size][]" class="wc_input_price" required>
    </td>
    <td>
        <input type="text" name="packing[height_box_size][]" class="wc_input_price" required>
    </td>
    <td>
        <input type="text" name="packing[max_weight_box_size][]" class="wc_input_price" required>
    </td>
</tr>
HTML;


if (isset($general_settings['packing']['name_box_size'])){
    $rows = '';
    for ($i = 0; $i < count($general_settings['packing']['name_box_size']); $i++ ){
        $rows .= <<<HTML
<tr>
    <td>
        <input type="checkbox" class="chosen_box">
    </td>
    <td>
        <input type="text" name="packing[name_box_size][]" value="{$general_settings['packing']['name_box_size'][$i]}" required>
    </td>
    <td>
        <input type="text" name="packing[length_box_size][]" value="{$general_settings['packing']['length_box_size'][$i]}" class="wc_input_price" required>
    </td>
    <td>
        <input type="text" name="packing[width_box_size][]" value="{$general_settings['packing']['width_box_size'][$i]}" class="wc_input_price" required>
    </td>
    <td>
        <input type="text" name="packing[height_box_size][]" value="{$general_settings['packing']['height_box_size'][$i]}" class="wc_input_price" required>
    </td>
    <td>
        <input type="text" name="packing[max_weight_box_size][]" value="{$general_settings['packing']['max_weight_box_size'][$i]}" class="wc_input_price" required>
    </td>
</tr>
HTML;

    }

}



$htmlRates = <<<HTML
<table>
    <tr id="sizes_options" valign="top">
        <td class="titledesc" colspan="2" style="padding-top:40px;padding-left:0px;">
            <strong>Tamaño de las cajas</strong><br><br>
            <table class="widefat">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Largo</th>
                        <th>Ancho</th>
                        <th>Alto</th>
                        <th>Peso Máximo</th>
                    </tr>
                </thead>
                    <tbody>
                        $rows
                    </tbody>
                    <tfoot>
    <tr>
      <td><button type="button" class="button-secondary add">Agregar Caja</button></td>
      <td><button type="button" class="button-secondary remove">Remover cajas selecionadas</button></td>
    </tr>
  </tfoot>
            </table>
        </td>
    </tr>
</table>
HTML;

return $htmlRates;