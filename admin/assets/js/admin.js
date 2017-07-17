(function ( $ ) {
	"use strict";

	$(function () {

		/**
		 * Save single product line in stock table
		 *
		 */              
    jQuery('.save-product').on('click', function(){
       jQuery('.lineloader').css('display','block');
       var product = jQuery(this).data('product');
       
       
       var sku           = jQuery('.sku_' + product).val();
       var manage_stock  = jQuery('.manage_stock_' + product).val();
       var stock_status  = jQuery('.stock_status_' + product).val();
       var backorders    = jQuery('.backorders_' + product).val();
       var stock         = jQuery('.stock_' + product).val();
       var regular_price = jQuery('.regular_price_' + product).val();
       var sales_price   = jQuery('.sales_price_' + product).val();
       var weight        = jQuery('.weight_' + product).val();
       var secure        = jQuery('.wsm-ajax-nonce_' + product).val();
       
       
       var data = {
            action       : 'save_one_product',
            product      : product,
            sku          : sku,
            manage_stock : manage_stock,
            stock_status : stock_status,
            backorders   : backorders,
            stock        : stock,
            regular_price: regular_price,
            sales_price  : sales_price,
            weight       : weight,
            secure       : secure
       };


        jQuery.post(ajaxurl, data, function(response){
           
          jQuery('.lineloader').css('display','none'); 
        
        });
       
    });
    
    
    /**
     * Show variations of selected product
     *
     */ 
    jQuery('.show-variable').on('click', function(){
       var variable = jQuery(this).data('variable');
       jQuery('.variation-item-' + variable).toggleClass('show-variations');
              
    });                 
    
    
    /**
     * Navigation
     *
     */          
    jQuery('.stock-manager-navigation li span').on('click', function(){
        jQuery('.stock-manager-navigation li span').removeClass('activ');
        jQuery(this).addClass('activ');
    });
    jQuery('.stock-manager-navigation li span.navigation-filter-default').on('click', function(){
        jQuery('.filter-block').removeClass('active-filter');
        jQuery('.stock-filter').addClass('active-filter');
    });
    jQuery('.stock-manager-navigation li span.navigation-filter-by-sku').on('click', function(){
        jQuery('.filter-block').removeClass('active-filter');
        jQuery('.filter-by-sku').addClass('active-filter');
    });
    jQuery('.stock-manager-navigation li span.navigation-filter-by-title').on('click', function(){
        jQuery('.filter-block').removeClass('active-filter');
        jQuery('.filter-by-title').addClass('active-filter');
    });
    jQuery('.stock-manager-navigation li span.navigation-filter-display').on('click', function(){
        jQuery('.filter-block').removeClass('active-filter');
        jQuery('.filter-display').addClass('active-filter');
    });


        //Open box for product title saving
        jQuery('.table_name_box .dashicons').on('click', function(){      
            var item = jQuery(this).data('item');
            jQuery('.item-post-title-wrap-'+item).css('display','block');
        });
        //Close box for product title saving
        jQuery('.item-post-title-button-close').on('click', function(){
            jQuery(this).parent().css('display', 'none');
        });
        //Save product title
        jQuery('.item-post-title-button').on('click', function(ajax_object){
           
            var item = jQuery(this).data('item');
            var title = jQuery('.item-post-title-'+item).val();
            var data = {
                action  : 'wsm_save_title_product',
                item    : item,
                title   : title,
                secure  : ajax_object.ajax_nonce
            };
            jQuery('.item-post-title-'+item).val(title); 
            jQuery('.item-post-link-'+item).text(title);  
            
            jQuery.post(ajaxurl, data, function(response, item, title){   
                jQuery('.item-post-title-wrap-'+response).css('display', 'none');      
            });
            
        });



        //Open box for sku saving
        jQuery('.item_sku_box .dashicons').on('click', function(){      
            var item = jQuery(this).data('item');
            jQuery('.item-sku-wrap-'+item).css('display','block');
        });
        //Close box for sku saving
        jQuery('.item-sku-button-close').on('click', function(){
            jQuery(this).parent().css('display', 'none');
        });
        //Save sku
        jQuery('.item-sku-button').on('click', function(ajax_object){
           
            var item = jQuery(this).data('item');
            var sku = jQuery('.sku_'+item).val();
            var data = {
                action  : 'wsm_save_sku',
                item    : item,
                sku     : sku,
                secure  : ajax_object.ajax_nonce
            };
            jQuery('.sku_-'+item).val(sku); 
            jQuery('.item-sku-text-'+item).text(sku);  
            
            jQuery.post(ajaxurl, data, function(response, item, sku){   
                jQuery('.item-sku-wrap-'+response).css('display', 'none');      
            });
            
        });


	});

}(jQuery));