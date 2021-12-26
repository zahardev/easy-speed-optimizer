<?php
/**
 * @var Settings_Tab[] $tabs
 * @var Settings_Tab $current_tab
 * @var string $page_slug
 * @var string $settings_title
 * */

use Espdopt\Entities\Settings_Tab;

?>

<div id="dso-settings" class="dso-settings">
    <form action='options.php' method='post'>

        <h1><?php echo $settings_title; ?></h1>

        <ul class="dso-tabs">
            <?php foreach ( $tabs as $tab ) : ?>
                <li class="dso-tab <?php echo ( $tab->id === $current_tab->id ) ? 'active' : '' ?>">
                    <a href="<?php echo esc_attr( $tab->get_url() ) ?>"><?php echo $tab->title ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ( $current_tab->description ): ?>
            <p style="font-size: 16px;"><?php echo $current_tab->description; ?></p>
        <?php endif; ?>

        <?php
        settings_fields( $page_slug );
        do_settings_sections( $page_slug );
        do_action( 'espdopt_tab_settings', $current_tab );
        submit_button();
        ?>
    </form>
</div>
