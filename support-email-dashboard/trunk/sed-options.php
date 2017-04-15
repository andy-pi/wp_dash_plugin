<?php
class SupportEmailDash
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Support Email Dashboard', 
            'manage_options', 
            'support-email-dash-settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'support_email_settings' );
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'support-email-dash-settings' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'support_email_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Support Email Dashboard Settings (by AndyPi.co.uk)', // Title
            array( $this, 'print_section_info' ), // Callback
            'support-email-dash-settings' // Page
        );  

        add_settings_field(
            'title', 
            'Title of Dashboard Widget', 
            array( $this, 'title_callback' ), 
            'support-email-dash-settings', 
            'setting_section_id'
        );      
        
        add_settings_field(
            'message_dashboard', 
            'Message to display on Dashboard Widget', 
            array( $this, 'message_dashboard_callback' ), 
            'support-email-dash-settings', 
            'setting_section_id'
        );  
        
        add_settings_field(
            'support_email_recipient', 
            'Support Email Address', 
            array( $this, 'support_email_recipient_callback' ), 
            'support-email-dash-settings', 
            'setting_section_id'
        );  
        
        add_settings_field(
            'support_email_subject', 
            'Email Subject (e.g. blog support request)', 
            array( $this, 'support_email_subject_callback' ), 
            'support-email-dash-settings', 
            'setting_section_id'
        ); 
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        
        $new_input = array();
        
        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );
            
        if( isset( $input['message_dashboard'] ) )
            $new_input['message_dashboard'] = sanitize_text_field( $input['message_dashboard'] );
        
        if( isset( $input['support_email_recipient'] ) )
            $new_input['support_email_recipient'] = sanitize_text_field( $input['support_email_recipient'] );
            
        if( isset( $input['support_email_subject'] ) )
            $new_input['support_email_subject'] = sanitize_text_field( $input['support_email_subject'] );
            
        
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Admin users enter your settings below. This will place a widget on your dashboard allowing users to email you for support from directly within WordPress.';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="support_email_settings[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }
    
        public function message_dashboard_callback()
    {
        printf(
            '<input type="text" id="message_dashboard" name="support_email_settings[message_dashboard]" value="%s" />',
            isset( $this->options['message_dashboard'] ) ? esc_attr( $this->options['message_dashboard']) : ''
        );
    }
    
        public function support_email_recipient_callback()
    {
        printf(
            '<input type="text" id="support_email_recipient" name="support_email_settings[support_email_recipient]" value="%s" />',
            isset( $this->options['support_email_recipient'] ) ? esc_attr( $this->options['support_email_recipient']) : ''
        );
    }
    
        public function support_email_subject_callback()
    {
        printf(
            '<input type="text" id="support_email_subject" name="support_email_settings[support_email_subject]" value="%s" />',
            isset( $this->options['support_email_subject'] ) ? esc_attr( $this->options['support_email_subject']) : ''
        );
    }
    
}

if( is_admin() )
    $my_settings_page = new SupportEmailDash();