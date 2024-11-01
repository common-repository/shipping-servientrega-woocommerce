<?php

$tab = (!empty($_GET['subtab'])) ? esc_attr($_GET['subtab']) : 'general';

$html = <<<HTML
<div class="wrap">
    <hr class="wp-header-end"/>
HTML;
$html .= $this->servientrega_shipping_page_tabs($tab);

if (in_array($tab, $this->name_tabs()))
    $html .= require_once($this->add_tab_per_file($tab));
$html .= <<<HTML
</div>
HTML;
echo $html;