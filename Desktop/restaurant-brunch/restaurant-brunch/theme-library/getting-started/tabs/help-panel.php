<?php
/**
 * Help Panel.
 *
 * @package restaurant_brunch
 */
?>

<div id="help-panel" class="panel-left visible">
    <div id="#help-panel" class="steps">  
        <h4 class="c">
            <?php esc_html_e( 'Quick Setup for Home Page', 'restaurant-brunch' ); ?>
            <a href="<?php echo esc_url( 'https://demo.asterthemes.com/docs/restaurant-brunch-free/' ); ?>" class="button button-primary" style="margin-left: 5px; margin-right: 10px;" target="_blank"><?php esc_html_e( 'Free Documentation', 'restaurant-brunch' ); ?></a>
        </h4>
        <hr class="quick-set">
        <p><?php esc_html_e( '1) Go to the Dashboard. navigate to pages, add a new one, and label it "home" or whatever else you like.The page has now been created.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '2) Go back to the Dashboard and then select Settings.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '3) Then Go to readings in the setting.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '4) There are 2 options your latest post or static page.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '5) Select static page and select from the dropdown you wish to use as your home page, save changes.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '6) You can set the home page in this manner.', 'restaurant-brunch' ); ?></p>
        <hr>
        <h4><?php esc_html_e( 'Setup Banner Section', 'restaurant-brunch' ); ?></h4>
        <hr class="quick-set">
        <p><?php esc_html_e( '1) Go to Dashboard > Go to Appereance > then Go to Customizer.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '2) In Customizer > Go to Front Page Options > Go to Banner Section.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '4) In Banner Section > Enable the Toggle button > and fill the following details.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '5) In this way you can set Banner Section.', 'restaurant-brunch' ); ?></p>
        <hr>
        <h4><?php esc_html_e( 'Setup Product Section', 'restaurant-brunch' ); ?></h4>
        <hr class="quick-set">
        <p><?php esc_html_e( '1) Go to Dashboard > Appearance > then Customizer.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '2) In Customizer > Go to Front Page Options > then go to Product Section.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '3) In Product Section > Enable the Toggle button to activate the section.', 'restaurant-brunch' ); ?></p>
        <p><?php esc_html_e( '4) In this way, you can set up the Product Section.', 'restaurant-brunch' ); ?></p>
    </div>
    <hr>
    <div class="custom-setting">
        <h4><?php esc_html_e( 'Quick Customizer Settings', 'restaurant-brunch' ); ?></h4>
        <span><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" target="_blank" class=""><?php esc_html_e( 'Customize', 'restaurant-brunch' ); ?></a></span>
    </div>
    <hr>
   <div class="setting-box">
        <div class="custom-links">
            <div class="icon-box">
                <span class="dashicons dashicons-admin-site-alt3"></span>
            </div>
            <h5><?php esc_html_e( 'Site Logo', 'restaurant-brunch' ); ?></h5>
            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=custom_logo' ) ); ?>" target="_blank" class=""><?php esc_html_e( 'Customize', 'restaurant-brunch' ); ?></a>
            
        </div>
        <div class="custom-links">
            <div class="icon-box">
                <span class="dashicons dashicons-color-picker"></span>
            </div>
            <h5><?php esc_html_e( 'Color', 'restaurant-brunch' ); ?></h5>
            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=primary_color' ) ); ?>" target="_blank" class=""><?php esc_html_e( 'Customize', 'restaurant-brunch' ); ?></a>
            
        </div>
        <div class="custom-links">
            <div class="icon-box">
                <span class="dashicons dashicons-screenoptions"></span>
            </div>
            <h5><?php esc_html_e( 'Theme Options', 'restaurant-brunch' ); ?></h5>
            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=restaurant_brunch_theme_options' ) ); ?>"target="_blank" class=""><?php esc_html_e( 'Customize', 'restaurant-brunch' ); ?></a>
            
        </div>
    </div>
    <div class="setting-box">
        <div class="custom-links">
            <div class="icon-box">
                <span class="dashicons dashicons-format-image"></span>
            </div>
            <h5><?php esc_html_e( 'Header Image ', 'restaurant-brunch' ); ?></h5>
            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=header_image' ) ); ?>" target="_blank" class=""><?php esc_html_e( 'Customize', 'restaurant-brunch' ); ?></a>
            
        </div>
        <div class="custom-links">
            <div class="icon-box">
                <span class="dashicons dashicons-align-full-width"></span>
            </div>
            <h5><?php esc_html_e( 'Footer Options ', 'restaurant-brunch' ); ?></h5>
            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=restaurant_brunch_footer_copyright_text' ) ); ?>" target="_blank" class=""><?php esc_html_e( 'Customize', 'restaurant-brunch' ); ?></a>
            
        </div>
        <div class="custom-links">
            <div class="icon-box">
                <span class="dashicons dashicons-admin-page"></span>
            </div>
            <h5><?php esc_html_e( 'Front Page Options', 'restaurant-brunch' ); ?></h5>
            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=restaurant_brunch_front_page_options' ) ); ?>" target="_blank" class=""><?php esc_html_e( 'Customize', 'restaurant-brunch' ); ?></a>
            
        </div>
    </div>
</div>


