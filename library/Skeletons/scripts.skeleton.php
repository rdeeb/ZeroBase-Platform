
function <?php echo $key ?>_script_loader()
{
<?php foreach( $scripts as $name => $config ): ?>
    wp_enqueue_scripts(
    <?php echo $name ?>,
    <?php echo $config['path'] ?>,
    <?php echo var_export( $config['dependencies'], true ) ?>,
    <?php echo $config['version'] ?>,
    <?php echo $config['in_footer'] ?>
    );

<?php endforeach; ?>
}

<?php if ( !empty( $admin ) ): ?>
function <?php echo $key ?>_script_admin_loader()
{
<?php foreach( $admin as $name => $config ): ?>
    wp_enqueue_scripts(
    <?php echo $name ?>,
    <?php echo $config['path'] ?>,
    <?php echo $config['dependencies'] ?>,
    <?php echo $config['version'] ?>,
    <?php echo $config['in_footer'] ?>
    );

<?php endforeach; ?>
}
<?php endif; ?>

add_action( 'wp_enqueue_scripts', '<?php echo $key ?>_script_loader' );
<?php if ( !empty( $admin ) ): ?>
add_action( 'admin_enqueue_scripts', '<?php echo $key ?>_script_admin_loader' );
<?php endif;
