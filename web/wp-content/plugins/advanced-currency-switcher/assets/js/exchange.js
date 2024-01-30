
( function ( $ ) {
    $( document ).on('click', '.cs-authenticate', function() {

        var api_key = $('.cs-input-appid').val() || '';
        var btn = $(this);
        btn.addClass('updating-message');
        btn.val('Authenticating..');
        $.ajax({
            url : csExchangeVars.ajax_url,
            type : 'POST',
            data : {
                action : 'ccs_validate',
                api_key : api_key,
                security :csExchangeVars.ajax_nonce
            },
            success : function( response ) {
                if( response.success ) {
                    btn.val(response.data);
                } else {
                    btn.val(response.data);
                }
            }
        });
    });

} )(jQuery);

function manual_api_form() {

    if( document.getElementById( "cswp_currency_form" ) !== null ) {

        var cswp_selectedvalue = document.getElementById( "cswp_currency_form" ).value;
        if ( cswp_selectedvalue==="apirate" ) {

            document.getElementById( "cs-api-display" ).style.display = "block";
            var cswp_api_textbox = document.getElementsByClassName('cswp_api_field');
            for (var i=0;i<cswp_api_textbox.length;i+=1){
              cswp_api_textbox[i].style.display = 'block';
            }
            var cswp_manual_textbox = document.getElementsByClassName('cswp_manual_field');
            for (var i=0;i<cswp_manual_textbox.length;i+=1){
              cswp_manual_textbox[i].style.display = 'none';
            }

           document.getElementById( "cs-manual-display" ).style.display = "block";

           } else {

           document.getElementById( "cs-manual-display" ).style.display = "block";
           document.getElementById( "cs-api-display" ).style.display = "none";
            var cswp_api_textbox = document.getElementsByClassName('cswp_api_field');
            for (var i=0;i<cswp_api_textbox.length;i+=1){
              cswp_api_textbox[i].style.display = 'none';
            }
            var cswp_manual_textbox = document.getElementsByClassName('cswp_manual_field');
            for (var i=0;i<cswp_manual_textbox.length;i+=1){
              cswp_manual_textbox[i].style.display = 'block';
            }
        }
    }

}

window.addEventListener( 'load', function() {

    manual_api_form();

} );

//js for hide and display manula and API currency convert value.
function showcurency( selectvalue ) {
    //console.log( document.getElementsByClassName( "cswp_api_field" ) );
    
    if ( selectvalue ) {

        optionvalue = document.getElementById( "api-currency" ).value;

        if ( optionvalue === selectvalue.value ) {


            var cswp_api_textbox = document.getElementsByClassName('cswp_api_field');
            for (var i=0;i<cswp_api_textbox.length;i+=1){
              cswp_api_textbox[i].style.display = 'block';
            }
            var cswp_manual_textbox = document.getElementsByClassName('cswp_manual_field');
            for (var i=0;i<cswp_manual_textbox.length;i+=1){
              cswp_manual_textbox[i].style.display = 'none';
            }


            document.getElementById( "cs-manual-display" ).style.display = "block";
            document.getElementById( "cs-api-display" ).style.display = "block";
            document.getElementById("cswp-apitext").required = true;
            jQuery(".cs-input-appid").attr('required','required');

          } else {

            document.getElementById( "cs-manual-display" ).style.display = "block";
            document.getElementById( "cs-api-display" ).style.display = "none";
            document.getElementById("cswp-apitext").required = false;
            jQuery(".cs-input-appid").removeAttr('required');

             var cswp_api_textbox = document.getElementsByClassName('cswp_api_field');
            for (var i=0;i<cswp_api_textbox.length;i+=1){
              cswp_api_textbox[i].style.display = 'none';
            }
            var cswp_manual_textbox = document.getElementsByClassName('cswp_manual_field');
            for (var i=0;i<cswp_manual_textbox.length;i+=1){
              cswp_manual_textbox[i].style.display = 'block';
            }

          }
    }
}
