<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Add meta boxes to selected post types
 *
 * @since 1.0
 */
function easy_slider_add_meta_box() {

    $post_types = easy_slider_allowed_post_types();

    if ( ! $post_types )
        return;

    foreach ( $post_types as $post_type => $status ) {
        add_meta_box( 'easy_slider', apply_filters( 'easy_slider_meta_box_title', __( 'Image Gallery', 'easy-slider' ) ), 'easy_slider_metabox', $post_type, apply_filters( 'easy_slider_meta_box_context', 'normal' ), apply_filters( 'easy_slider_meta_box_priority', 'low' ) );
    }

}
add_action( 'add_meta_boxes', 'easy_slider_add_meta_box' );


/**
 * Render gallery metabox
 *
 * @since 1.0
 */
function easy_slider_metabox() {

    global $post;
    $DB = new DB();

?>

    <div id="gallery_images_container">

        <ul class="gallery_images">
            <?php

    $attachments = $DB->getAttachments($post->ID);

    $image_gallery = [];
    $number = 1;
    if ( $attachments )
        foreach ( $attachments as $one_attachment ) {
            $attachment_id = $one_attachment['image_id'];
            $image_gallery[] = $number;
            echo '<li class="image attachment details" data-attachment_id="' . $attachment_id . '" data-number="' . $number . '">
                    <input type="hidden" name="slider_ID_[]" value="' . $one_attachment['ID'] . '">
                    <input type="hidden" name="slider_number_[]" value="' . $number . '"/>
                    <input type="hidden" name="easy_slider_attachment_id_[]" value="' . $attachment_id . '"/>
                    <div class="attachment-preview">
                        <div class="thumbnail"><img src="'. $one_attachment['image_src']['sizes']["thumbnail"]['file'] . '" height="150" width="150"/></div>
                        <a href="#" class="delete check" title="' . __( 'Remove image', 'easy-slider' ) . '"><div class="media-modal-icon"></div></a>
                        <div class="slider_desc">
                            <table>
                                <tr>
                                    <th><label for="slider_title_' . $one_attachment['ID'] . '">Slider title</label></th>
                                    <td><input name="slider_title_[]" id="slider_title_' . $one_attachment['ID'] . '" type="text" value="' . $one_attachment['image_title'] . '"/></td>
                                    <th><label for="slider_subtitle_' . $one_attachment['ID'] . '">Slider subtitle</label></th>
                                    <td><input name="slider_subtitle_[]>" id="slider_subtitle_' . $one_attachment['ID'] . '" type="text" value="' . $one_attachment['image_subtitle'] . '"/></td>
                                </tr>
                                <tr>
                                    <th><label for="button_name_' . $one_attachment['ID'] . '">Button name</label></th>
                                    <td><input name="button_name_[]" id="button_name_' . $one_attachment['ID'] . '" type="text" value="' . $one_attachment['button_name'] . '"/></td>
                                    <th><label for="button_link' . $one_attachment['ID'] . '">Button link</label></th>
                                    <td><input name="button_link_[]>" id="button_link_' . $one_attachment['ID'] . '" type="text" value="' . $one_attachment['button_link'] . '"/></td>
                                </tr>
                                <tr>
                                    <th colspan="1"><label for="image_signature_' . $one_attachment['ID'] . '">Image signature</label></th>
                                    <td colspan="3"><input name="image_signature_[]" id="image_signature_' . $one_attachment['ID'] . '" type="text" value="' . $one_attachment['image_signature'] . '"/></td>
                                </tr>
                                <tr>
                                    <th colspan="1"><label for="slider_content_' . $one_attachment['ID'] . '">Slider content</label></th>
                                    <td colspan="3"><textarea name="slider_content_[]" id="slider_content_' . $one_attachment['ID'] . '">' . $one_attachment['image_content'] . '</textarea></td>
                                </tr>
                             </table>
                        </div>
                    </div>
                </li>';

            $number++;
       }

        $image_gallery = implode(', ', $image_gallery);
?>
        </ul>


        <input type="hidden" id="image_gallery" name="image_gallery" value="<?php echo esc_attr( $image_gallery ); ?>" />
        <?php wp_nonce_field( 'easy_slider', 'easy_slider' ); ?>

    </div>

    <p class="add_gallery_images hide-if-no-js">
        <a href="#"><?php _e( 'Add gallery images', 'easy-slider' ); ?></a>
    </p>


    <script type="text/javascript">
        var number = <?php echo $number;?>;

        jQuery(document).ready(function($){

            // Uploading files
            var image_gallery_frame;
            var $image_gallery_ids = $('#image_gallery');
            var $gallery_images = $('#gallery_images_container ul.gallery_images');

            jQuery('.add_gallery_images').on( 'click', 'a', function( event ) {

                var $el = $(this);
                var attachment_ids = $image_gallery_ids.val();

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( image_gallery_frame ) {
                    image_gallery_frame.open();
                    return;
                }

                // Create the media frame.
                image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                    // Set the title of the modal.
                    title: '<?php _e( 'Add Images to Gallery', 'easy-slider' ); ?>',
                    button: {
                        text: '<?php _e( 'Add to gallery', 'easy-slider' ); ?>',
                    },
                    multiple: true
                });

                // When an image is selected, run a callback.
                image_gallery_frame.on( 'select', function() {

                    var selection = image_gallery_frame.state().get('selection');

                    selection.map( function( attachment ) {

                        attachment = attachment.toJSON();

                        if ( attachment.id ) {
                            attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

                            $.ajax({
                                url: '<?php echo EASY_SLIDER_URL?>',
                                data: {'add_slide': true},
                                type: 'POST',   
                                dataType : "html", 
                                success: function (data, textStatus) { 
 
                                }               
                            });

                            $gallery_images.append('\
                                <li class="image attachment details" data-attachment_id="' + attachment.id + '" data-number="' + number + '">\
                                    <input type="hidden" name="slider_number[]" value="' + number + '"/>\
                                    <input type="hidden" name="easy_slider_attachment_id[]" value="' + attachment.id + '"/>\
                                    <div class="attachment-preview">\
                                        <div class="thumbnail">\
                                            <img src="' + attachment.url + '" />\
                                        </div>\
                                        <a href="#" class="delete check" title="<?php _e( 'Remove image', 'easy-slider' ); ?>"><div class="media-modal-icon"></div></a>\
                                        <div class="slider_desc">\
                                            <table>\
                                                <tr>\
                                                    <th><label>Slider title</label></th>\
                                                    <td><input name="slider_title[]" type="text"/></td>\
                                                    <th><label>Slider subtitle</label></th>\
                                                    <td><input name="slider_subtitle[]" type="text"/></td>\
                                                </tr>\
                                                <tr>\
                                                    <th><label>Button name</label></th>\
                                                    <td><input name="button_name[]" type="text"/></td>\
                                                    <th><label>Button link</label></th>\
                                                    <td><input name="button_link[]" type="text"/></td>\
                                                </tr>\
                                                <tr>\
                                                    <th colspan="1"><label>Image signature</label></th>\
                                                    <td colspan="3"><input name="image_signature[]" type="text"/></td>\
                                                </tr>\
                                                <tr>\
                                                    <th colspan="1"><label>Slider content</label></th>\
                                                    <td colspan="3"><textarea name="slider_content[]"></textarea></td>\
                                                </tr>\
                                            </table>\
                                        </div>\
                                    </div>\
                                </li>');
                        }
                    } );

                    $image_gallery_ids.val( attachment_ids );

                    number++;
                });

                // Finally, open the modal.
                image_gallery_frame.open();
            });

            // Image ordering
            $gallery_images.sortable({
                items: 'li.image',
                cursor: 'move',
                scrollSensitivity:40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'eig-metabox-sortable-placeholder',
                start:function(event,ui){
                    ui.item.css('background-color','#f6f6f6');
                },
                stop:function(event,ui){
                    ui.item.removeAttr('style');
                },
                update: function(event, ui) {
                    var numbers = '';

                    $('#gallery_images_container ul li.image').css('cursor','default').each(function() {
                        var number = jQuery(this).attr( 'data-number' );
                        numbers = numbers + number + ',';
                    });

                    $image_gallery_ids.val( numbers );
                }
            });

            // Remove images
            $('#gallery_images_container').on( 'click', 'a.delete', function() {

                $(this).closest('li.image').remove();

                var numbers = '';

                $('#gallery_images_container ul li.image').css('cursor','default').each(function() {
                    var number = jQuery(this).attr( 'data-number' );
                    numbers = numbers + number + ',';
                });

                $image_gallery_ids.val( numbers );

                return false;
            } );

        });
    </script>
    <?php
}


/**
 * Save function
 *
 * @since 1.0
 */
function easy_slider_save_post( $post_id ) {
    $DB = new DB();
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    $post_types = easy_slider_allowed_post_types();

    // check user permissions
    if ( isset( $_POST[ 'post_type' ] ) && !array_key_exists( $_POST[ 'post_type' ], $post_types ) ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
    }
    else {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    }

    if ( ! isset( $_POST[ 'easy_slider' ] ) || ! wp_verify_nonce( $_POST[ 'easy_slider' ], 'easy_slider' ) )
        return;

    if ( isset( $_POST[ 'image_gallery' ] ) && !empty( $_POST[ 'image_gallery' ] ) ) {

        $order_ids = sanitize_text_field( $_POST['image_gallery'] );
        // turn comma separated values into array
        $order_ids = explode( ',', $order_ids );

        //remove old
        $idsToDelete = [];
        $slide_IDS = $DB->getAttachmentsIDs($post_id);
        foreach($slide_IDS as $one_slide_id){
            if(!in_array($one_slide_id, $_POST['slider_ID_'])){
                 $idsToDelete[] = $one_slide_id;
            }
        }
        //var_dump($idsToDelete); exit;
        if(count($idsToDelete)){
            $DB->removeAttachments($idsToDelete);
        }
        

        //add new
        if(isset($_POST['easy_slider_attachment_id'])){
            foreach($_POST['easy_slider_attachment_id'] as $key => $value){
                $order = array_search($_POST['slider_number'][$key],  $order_ids);
                if($order === false){
                    $order = 100;
                }

                $arr_to_add = array(
                    'post_id' => $post_id,
                    'image_id' => $value,
                    'image_order' => $order,
                    'image_title' => $_POST['slider_title'][$key],
                    'image_subtitle' => $_POST['slider_subtitle'][$key],
                    'image_signature' => $_POST['image_signature'][$key],
                    'image_content' => $_POST['slider_content'][$key],
                    'button_name' => $_POST['button_name'][$key],
                    'button_link' => $_POST['button_link'][$key],
                );
                $DB->addAttachments($arr_to_add);
            }
        }
       


        //update others
        foreach ($_POST['slider_ID_'] as $key => $value) {
            $order = array_search($_POST['slider_number_'][$key],  $order_ids);
            if($order === false){
                $order = 100;
            }

            $arr_to_edit = array(
                'ID' => $value,
                'post_id' => $post_id,
                'image_id' => $_POST['easy_slider_attachment_id_'][$key],
                'image_order' => $order,
                'image_title' => $_POST['slider_title_'][$key],
                'image_subtitle' => $_POST['slider_subtitle_'][$key],
                'image_signature' => $_POST['image_signature_'][$key],
                'image_content' => $_POST['slider_content_'][$key],
                'button_name' => $_POST['button_name_'][$key],
                'button_link' => $_POST['button_link_'][$key]
            );

            $DB->editAttachments($arr_to_edit);
            
        }
    } else {
        $DB->cleanAttachments($post_id);
    }
}
add_action( 'save_post', 'easy_slider_save_post' );