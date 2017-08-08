<?php

function pmwi_wp_all_import_existing_meta_keys( $existing_meta_keys, $custom_type )
{	
	if ( $custom_type == 'product')
	{
        $hide_fields = array('_stock_status', '_featured', '_visibility', '_regular_price_tmp', '_sale_price_tmp', '_sale_price_dates_from_tmp', '_sale_price_dates_from_tmp', '_sale_price_dates_to_tmp', '_price_tmp', '_stock_tmp', '_stock_status_tmp', '_product_image_gallery_tmp');
        foreach ($existing_meta_keys as $key => $value) {
            if ( in_array($value, $hide_fields) || strpos($value, '_v_') === 0 ) unset($existing_meta_keys[$key]);
        }
	}	
	return $existing_meta_keys;
}