<div class="zb-tabbed-metabox uk-grid uk-form uk-form-staccked">
    <ul class="uk-tab uk-tab-left uk-width-1-4" data-uk-tab="{connect:'#zb-metabox-<?php echo $id ?>'}">
        <?php $i=0; foreach($tabs as $key => $data): ?>
            <li <?php if ($i==0): ?>class="uk-active"<?php endif; ?>>
                <a href="#"><span><?php if($data['icon'] != NULL): ?><i class="<?php echo $data['icon'] ?>"></i> <?php endif; ?><?php echo $data['label']; $i++; ?></span></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <ul id="zb-metabox-<?php echo $id ?>" class="uk-switcher uk-tab-left uk-width-3-4">
        <?php $i=0;foreach($tabs as $key => $data): ?>
            <li <?php if ($i==0): ?>class="uk-active"<?php endif; ?>>
                <?php $i++; foreach( $data['fields'] as $field ): ?>
                    <?php echo $renderer->renderRow($field); ?>
                <?php endforeach; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
