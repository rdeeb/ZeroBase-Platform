$args = <?php echo var_export($args, true) ?>;
register_taxonomy( '<?php echo $taxonomy_name ?>', '<?php echo $attach_to ?>', $args );
<?php if ( !empty( $fields ) ): ?>
$tax_extender = new Zerobase\Taxonomy\TaxonomyExtender( '<?php echo $taxonomy_name ?>', <?php echo var_export($fields, true) ?> );
$tax_extender->register();
<?php endif;