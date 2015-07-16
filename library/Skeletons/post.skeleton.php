$args = <?php echo var_export($args, true) ?>;
register_post_type( '<?php echo $post_type_name ?>', $args );
