jQuery(function() {
   
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, { container: ".elex-ppct-wrap" });
    });
    //start off by hiding it
    jQuery("#elex-rqst-quote-light-box-input").hide();

    // when the Change EVENT fires - 
    jQuery("#elex-rqst-quote-form-select").change(function(event)

        {
            if (jQuery(this).val() == "light_box")
                jQuery("#elex-rqst-quote-light-box-input").show();
            else
                jQuery("#elex-rqst-quote-light-box-input").hide();
        });
});

jQuery(document).ready(function() {

    jQuery(function(){

        if (jQuery('#elex_ppct_discount_type').val() === 'no-discount') {
            jQuery('#elex_ppct_discount_amount').closest('tr').addClass('d-none');
        }

        // On change event of the discount type dropdown
        jQuery('#elex_ppct_discount_type').change(function() {
            if (jQuery(this).val() === 'no-discount') {
                // If "No Discount" is selected, hide the discount value field
                jQuery('#elex_ppct_discount_amount').closest('tr').addClass('d-none');
            } else {
                // If any other option is selected, show the discount value field
                jQuery('#elex_ppct_discount_amount').closest('tr').removeClass('d-none');
            }
        });

        //include products filter
        const elex_ppct_select_all_categories_id_include_products = jQuery('#elex_ppct_select_all_categories_id_include_products');
        elex_ppct_select_all_categories_id_include_products.on('change',function() {
            if ( this.checked ) {
                jQuery('.elex_custom_text_categories_include_products').prop('checked', true);
            } else {
                jQuery('.elex_custom_text_categories_include_products').prop('checked', false);
            }
        });

        jQuery( '.elex_custom_text_categories_include_products' ).on( 'change', function() {
            // Check if any checkbox is not checked
            if ( jQuery( '.elex_custom_text_categories_include_products' ).filter( ':not(:checked)' ).length > 0 ) {
                jQuery( '#elex_ppct_select_all_categories_id_include_products' ).prop( 'checked', false );
            } else {
                jQuery( '#elex_ppct_select_all_categories_id_include_products' ).prop( 'checked', true );
            }
        });

        var checkboxes = jQuery( '.elex_custom_text_categories_include_products' );
        // Check if any checkbox is not checked
        if ( checkboxes.filter( ':not(:checked)' ).length > 0 ) {
            jQuery( '#elex_ppct_select_all_categories_id_include_products' ).prop( 'checked', false );
        } else {
            jQuery( '#elex_ppct_select_all_categories_id_include_products' ).prop( 'checked', true );
        }

        jQuery('.elex_custom_text_categories_include_products').on('change', function() {
            const category_id = jQuery(this).val();
            jQuery(this).next('ul').find('input').each(function() {
                const checked = jQuery('#elex_category_' + category_id ).is(':checked');
                jQuery(this).prop('checked', checked);
            });
        });

         //exclude products filter
         const elex_ppct_select_all_categories_id_exclude_products = jQuery('#elex_ppct_select_all_categories_id_exclude_products');
         elex_ppct_select_all_categories_id_exclude_products.on('change',function() {
             if ( this.checked ) {
                 jQuery('.elex_custom_text_categories_exclude_products').prop('checked', true);
             } else {
                 jQuery('.elex_custom_text_categories_exclude_products').prop('checked', false);
             }
         });
 
         jQuery( '.elex_custom_text_categories_exclude_products' ).on( 'change', function() {
             // Check if any checkbox is not checked
             if ( jQuery( '.elex_custom_text_categories_exclude_products' ).filter( ':not(:checked)' ).length > 0 ) {
                 jQuery( '#elex_ppct_select_all_categories_id_exclude_products' ).prop( 'checked', false );
             } else {
                 jQuery( '#elex_ppct_select_all_categories_id_exclude_products' ).prop( 'checked', true );
             }
         });
 
         var checkboxes = jQuery( '.elex_custom_text_categories_exclude_products' );
         // Check if any checkbox is not checked
         if ( checkboxes.filter( ':not(:checked)' ).length > 0 ) {
             jQuery( '#elex_ppct_select_all_categories_id_exclude_products' ).prop( 'checked', false );
         } else {
             jQuery( '#elex_ppct_select_all_categories_id_exclude_products' ).prop( 'checked', true );
         }
 
         jQuery('.elex_custom_text_categories_exclude_products').on('change', function() {
             const category_id = jQuery(this).val();
             jQuery(this).next('ul').find('input').each(function() {
                 const checked = jQuery('#elex_category_' + category_id ).is(':checked');
                 jQuery(this).prop('checked', checked);
             });
         });

        // Select page(s) js code
        const elex_ppct_select_all_pages_id = jQuery('#elex_ppct_select_all_pages_id');
        elex_ppct_select_all_pages_id.on('change',function() {
            if ( this.checked ) {
                jQuery('.elex_ppct_page_type').prop('checked', true);
            } else {
                jQuery('.elex_ppct_page_type').prop('checked', false);
            }
        });

        jQuery( '.elex_ppct_page_type' ).on( 'change', function() {
            // Check if any checkbox is not checked
            if ( jQuery( '.elex_ppct_page_type' ).filter( ':not(:checked)' ).length > 0 ) {
                jQuery( '#elex_ppct_select_all_pages_id' ).prop( 'checked', false );
            } else {
                jQuery( '#elex_ppct_select_all_pages_id' ).prop( 'checked', true );
            }
        });

        var page_checkboxes = jQuery( '.elex_ppct_page_type' );
        // Check if any checkbox is not checked
        if ( page_checkboxes.filter( ':not(:checked)' ).length > 0 ) {
            jQuery( '#elex_ppct_select_all_pages_id' ).prop( 'checked', false );
        } else {
            jQuery( '#elex_ppct_select_all_pages_id' ).prop( 'checked', true );
        }
        // End

        if (jQuery('#elex_ppct_check_field').is(":checked")) {
            jQuery('#elex_ppct_prefix_field').closest("tr").show();
            jQuery('#elex_ppct_suffix_field').closest("tr").show();
            jQuery('#elex_ppct_discount_type').closest("tr").show();
            jQuery('#elex_ppct_discount_amount').closest("tr").show();
            jQuery('.elex_ppct_page_class').closest("tr").show();
            jQuery('.product_categories_class').show();
            jQuery('.elex-ppct-filter-products').removeAttr('hidden');
        } else {
            jQuery('#elex_ppct_prefix_field').closest("tr").hide();
            jQuery('#elex_ppct_discount_type').closest("tr").hide();
            jQuery('#elex_ppct_suffix_field').closest("tr").hide();
            jQuery('#elex_ppct_discount_amount').closest("tr").hide();
            jQuery('.elex_ppct_page_class').closest("tr").hide();
            jQuery('.product_categories_class').hide();
            jQuery('.elex-ppct-filter-products').attr('hidden', true);
        }
        
        jQuery("#elex_ppct_check_field").click(function(event){
            var value=jQuery(this).is(':checked');
            if(value === true ){
                jQuery('#elex_ppct_prefix_field').closest("tr").show();
                jQuery('#elex_ppct_discount_type').closest("tr").show();
                jQuery('#elex_ppct_suffix_field').closest("tr").show();
                jQuery('#elex_ppct_discount_amount').closest("tr").show();
                jQuery('.elex_ppct_page_class').closest("tr").show();
                jQuery('.product_categories_class').show();
                jQuery('.elex-ppct-filter-products').removeAttr('hidden');
            } else {
                jQuery('#elex_ppct_prefix_field').closest("tr").hide();
                jQuery('#elex_ppct_discount_type').closest("tr").hide();
                jQuery('#elex_ppct_suffix_field').closest("tr").hide();
                jQuery('#elex_ppct_discount_amount').closest("tr").hide();
                jQuery('.elex_ppct_page_class').closest("tr").hide();
                jQuery('.product_categories_class').hide();
                jQuery('.elex-ppct-filter-products').attr('hidden', true);
            }
        });
    });

    jQuery('.products_by_name').select2({
        minimumInputLength: 3,
        ajax:{
            url:elex_ppct_ajax_obj.ajax_url,
            type: 'POST',
            data: function (params) {
                var query = {
                    search: params.term,
                    action: 'search_products_by_name',
                    ppct_nonce : elex_ppct_ajax_obj.nonce
                }
               return query;
            },
            processResults: function (data) {
                return {
                    results: data.data,
                };
            }
  
        }
      
    });

    jQuery('.products_by_cat').select2({
        minimumInputLength: 3,
        ajax:{
            url:elex_ppct_ajax_obj.ajax_url,
            type: 'POST',
            
            data: function (params) {
                var query = {
                    search: params.term,
                    action: 'search_products_by_category',
                    ppct_nonce : elex_ppct_ajax_obj.nonce
                }
               return query;
            },
            processResults: function (data) {
                return {
                    results: data.data,
                };
            }
  
        }
      
    });

    jQuery('.products_by_tag').select2({
        minimumInputLength: 3,
        ajax:{
            url:elex_ppct_ajax_obj.ajax_url,
            type: 'POST',
            
            data: function (params) {
                var query = {
                    search: params.term,
                    action: 'search_products_by_tag',
                    ppct_nonce : elex_ppct_ajax_obj.nonce

                }
               return query;
            },
            processResults: function (data) {
                return {
                    results: data.data,
                };
            }
  
        }
      
    });

    jQuery('.include_roles , .exclude_roles , .req_order_status , #add_more_item_btn_redirection , #selected_page ').select2({
    });

    // widget page button label sub sub checkbox
    jQuery(".elex-ppct-widget-button-label-content").hide();
    jQuery(".elex-ppct-widget-button-label-check").change(function() {
        if (jQuery(this).is(":checked")) {
            jQuery(".elex-ppct-widget-button-label-content").show(300);
        } else {
            jQuery(".elex-ppct-widget-button-label-content").hide(200);
        }
    });
});



// Example starter JavaScript for disabling form submissions if there are invalid fields

(function() {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})();

jQuery(document).ready(function() {
    // Get the reference to the div with the class "my-div"
    var myDiv = jQuery('#quote_list');
    
    // var dyDiv = jQuery('.elex-raq-quote-details-container')
    // Function to add class based on div width
    function addClassBasedOnWidth() {
        var windowWidth = jQuery(window).width();
        if(windowWidth > 990){
            // Get the current width of the div
            var divWidth = myDiv.width();
            
            // Add the class "wide-div" if the width is greater than 400 pixels
            if (divWidth < 1100) {
                myDiv.addClass('elex-raq-quote-hide-names');
            } else {
                // Remove the class "wide-div" if the width is less than or equal to 400 pixels
                myDiv.removeClass('elex-raq-quote-hide-names');
            }
        }else{
            myDiv.removeClass('elex-raq-quote-hide-names');
        }
      
    }
    // Call the function on page load
  addClassBasedOnWidth();

  // Call the function on window resize to update the class if the div width changes
  jQuery(window).resize(function() {
    addClassBasedOnWidth();
  });
});