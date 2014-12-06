<div class="wrap">
    <h2><?php echo $page_name ?></h2>
    <form action="">
        <div class="uk-grid">
            <div class="uk-width-medium-1-5">
                <ul class="uk-tab uk-tab-left" data-uk-tab="{connect:'#zb-settings-content'}">
                    <?php $i=0;foreach($settings_pages as $key => $builder): ?>
                    <li <?php if ($i==0): ?>class="uk-active"<?php endif; ?>>
                        <a href="#"><?php echo $key; $i++; ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="uk-width-medium-4-5">
                <ul id="zb-settings-content" class="uk-switcher">
                    <?php $i=0;foreach($settings_pages as $key => $builder): ?>
                    <li <?php if ($i==0): ?>class="uk-active"<?php endif; ?>>
                        <table class="form-table zb-form-table">
                            <?php echo $builder->renderTr(); $i++; ?>
                        </table>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <p class="submit"><input name="submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Changes','wptuts_textdomain'); ?>" /></p>
    </form>
</div>