$args = <?php echo var_export($args, true) ?>;
register_taxonomy( '<?php echo $taxonomy_name ?>', '<?php echo $attach_to ?>', $args );
