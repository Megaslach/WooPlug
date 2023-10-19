<?php
add_action( 'wp_footer', 'update_cart_on_item_qty_change');
function update_cart_on_item_qty_change() {
    if (is_cart()) :
        ?>
        <script type="text/javascript">
            jQuery( function($){
                $(document.body).on('change input', '.qty', function(){
                    $('button[name="update_cart"]').trigger('click');
                    // console.log('Cart quantity changed…');
                });
            });
        </script>
    <?php
    endif;
}
?>

