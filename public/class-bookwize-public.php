<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.bookwize.com/
 * @since      1.0.0
 *
 * @package    Bookwize Integrated
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bookwize Integrated
 * @author     Your Name <email@example.com>
 */
class Bookwize_Integrated_Public
{

    public $settings = [];
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $bookwize The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $bookwize The ID of this plugin.
     */
    private $bookwize;
    /**
     * The version of this plugin.
     *d
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $_settings = [
        'debug' => false,
        'useCache' => true,
        'defaultCache' => 'sessionStorage',
        'cacheLevel' => 1,
        'lang' => 'en-US',
        // if we want all supported languages, set as en empty array
        'languages' => ['en-US'],
        'discount' => '%', // % = percentage, else numeric
        'currency' => 'EUR',
        'defaultBoardType' => 'BedAndBreakfast',
        'roomBackgroundImage' => '',
        'baseUrl' => '',
        'localizationUrl' => '',
        'googleAnalyticsEnabled' => true,
        'userCookieAcceptance' => true,
        'sortRoomsBy' => ['availability.desc'],
        'hotelId' => '',
        'apiKey' => '',
        'apiBaseUrl' => 'https://app.bookwize.com/api/v1.4',
        'pluginVersion' => '3.1'
    ];

    public function __construct($bookwize, $version)
    {

        $this->bookwize = $bookwize;
        $this->version = $version;

    }

    public function init()
    {
        global $post;
        add_action('wp_head', [&$this, 'wp_head'], 10, 2);
        add_filter('redirect_canonical', [&$this, 'redirect_canonical']);
        add_filter('body_class', [&$this, 'body_class']);
        add_action('template_redirect', [&$this, 'template_redirect']);
        if (isset($post) && (get_post_meta($post->ID, 'bookwize_integrated_page_type', true) === 'integrated')) {
            add_filter('the_content', 'bw_load_booking_engine');
        }
        add_action('bw_enqueue_scripts',[&$this, 'enqueue_styles_scripts']);
    }

    public function template_redirect()
    {
        if (bw_is_booking_page()) {
            $this->force_ssl();
        }
    }

    /**
     * @param $classes
     *
     * @return mixed
     */
    public function body_class($classes)
    {
        $classes[] = 'ibe';

        return $classes;
    }

    public function redirect_canonical($redirect_url)
    {
        if (bw_is_booking_page()) {
            $redirect_url = false;
        }

        return $redirect_url;
    }


    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Bookwize_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Bookwize_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */        
        $dependencies = [];

        if( !get_option('bw_disable_bootstrap')){
            wp_register_style($this->bookwize . '-bootstrap', plugin_dir_url(__FILE__) . 'vendor/bootstrap-3.3.6/css/bootstrap.min.css', [], $this->version, 'all');
            $dependencies[] = $this->bookwize . '-bootstrap';
        }
            
        if( !get_option('bw_disable_fontawesome')){
            wp_register_style($this->bookwize . '-font-awesome', plugin_dir_url(__FILE__) . 'vendor/font-awesome-4.6.3/css/font-awesome.min.css', [], $this->version, 'all');
            $dependencies[] = $this->bookwize . '-font-awesome';
        }
        wp_register_style($this->bookwize . '-jquery-datepick', plugin_dir_url(__FILE__) . 'vendor/jquery-datepick/jquery.datepick.css', [], $this->version, 'all');
        $dependencies[] = $this->bookwize . '-jquery-datepick';
        wp_register_style($this->bookwize . '-print', plugin_dir_url(__FILE__) . 'css/bookwize-print.css', [], $this->version, 'print');
        $dependencies[] = $this->bookwize . '-print';

        wp_register_style($this->bookwize, plugin_dir_url(__FILE__) . 'css/bookwize-public.css', $dependencies, $this->version, 'all');

        if (bw_is_booking_page()) {
            $this->enqueue_registered_styles();
        }

    }

    public function enqueue_registered_styles(){
        wp_enqueue_style($this->bookwize . '-bootstrap');
        wp_enqueue_style($this->bookwize . '-font-awesome');
        wp_enqueue_style($this->bookwize . '-jquery-datepick');
        wp_enqueue_style($this->bookwize . '-print');
        wp_enqueue_style($this->bookwize);
    }

    /**
     * Register the scripts for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Bookwize_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Bookwize_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_register_script($this->bookwize . '-vendors', 'https://bookwizecdn.azureedge.net/vendors/vendors-1.10.min.js', [], false, true); 
        wp_register_script($this->bookwize . '-vendors',  plugin_dir_url(__FILE__) . 'js/vendors.min.js', [], "1.10", true);
        //wp_register_script($this->bookwize . '-app', 'https://bookwizecdn.azureedge.net/app/ibe-5.3.15.min.js', [$this->bookwize . '-vendors'], false, true);
        wp_register_script($this->bookwize . '-loader', plugin_dir_url(__FILE__) . 'js/loader.js', [], "5.3.15", true);
        wp_register_script($this->bookwize . '-app', plugin_dir_url(__FILE__) . 'js/ibe.min.js', [$this->bookwize . '-vendors'], "5.3.15", true);
        wp_register_script($this->bookwize . '-site', plugin_dir_url(__FILE__) . 'js/bookwize-public.min.js', [
            $this->bookwize . '-app',
        ], false, true);

	    if( bw_is_booking_page() ){
            $this->enqueue_registered_scripts();
        }

    }

    public function enqueue_registered_scripts(){
        wp_enqueue_script($this->bookwize . '-vendors');
        wp_enqueue_script($this->bookwize . '-loader');
        wp_enqueue_script($this->bookwize . '-app');
        wp_enqueue_script($this->bookwize . '-site');
    }

    public function enqueue_styles_scripts(){
        $this->enqueue_registered_styles();
        $this->enqueue_registered_scripts();
    }

    public function wp_head()
    {
        $this->config();
        ?>
        <script type="text/javascript">
            //<![CDATA[
            <?php echo 'var IBEConfig = ' . json_encode($this->settings) . ';'; ?>        
            //]]>
        </script>
       
        <?php if(get_option('bw_googleAnalyticsId') !='' || get_option('bw_adwordsTrack') != '') { ?>
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo get_option('bw_googleAnalyticsId'); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());     
            </script>                               
        <?php } ?>    
        <?php if(get_option('bw_googleAnalyticsId') !='') { ?>
            <script>
                gtag('config', '<?php echo get_option('bw_googleAnalyticsId'); ?>', { 'anonymize_ip': true,  'send_page_view': false, 'linker': {'accept_incoming': true } });                  
            </script>
        <?php } ?>
        <?php if(get_option('bw_adwordsTrack') != '') { ?>
            <script>
                gtag('config', 'AW-<?php echo get_option('bw_adwordsTrack');?>', { 'anonymize_ip': true });
            </script>
        <?php } ?>
        <?php if(get_option('bw_facebookTrack') != '') { ?>
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return; n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                }; if (!f._fbq) f._fbq = n;
                n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0;
                t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s)
            }(window,
                document, 'script', '//connect.facebook.net/en_US/fbevents.js');

            fbq('init', '<?php echo get_option('bw_facebookTrack')?>');
            fbq('track', 'PageView');
        </script>
    <?php } ?>

        <?php
    }

    /**
     * IBE configuration variables.
     */
    protected function config()
    {
        $tmp_languages = (new Bookwize_Integrated_Languages())->get_languages();

        if (defined('ICL_LANGUAGE_CODE')) {
            $lang = ICL_LANGUAGE_CODE;
        }else {
            $lang = get_bloginfo("language");
        }

        $languages = [];
        if (!empty($tmp_languages)) {
            foreach ($tmp_languages as $code => $name) {
                $languages[] = $code;
            }
        } else {
            $languages[] = 'en';
        }

        $base_url = plugin_dir_url(__FILE__);
        $bw_apiKey = get_option('bw_apiKey');
        $bw_apiBaseUrl = 'https://app.bookwize.com/api/v1.4';
        $this->settings = array_merge($this->_settings, [
            'hotelId' => get_option('bw_hotelId'),
            'apiKey' => $bw_apiKey,
            'apiBaseUrl' => $bw_apiBaseUrl,
            'languages' => $languages,
            'lang' => $lang,
            'baseUrl' => $base_url,
            'bwButtonColor' => get_option('bw_button_color'),
            'roomBackgroundImage' => $base_url . 'img/missing-room-image.jpg',
            'localizationUrl' => $bw_apiBaseUrl . '/system/resources/?apikey=' . $bw_apiKey,
            'googleAnalyticsId' => get_option('bw_googleAnalyticsId'),
            'currency' => get_option('bw_currency'),
            'currencySelector' => true,
            'integrated_page' => $this->get_integrated_page(),
            'debug' => get_option('bw_debug'),
            'allCreditCards' => 'MasterCard,VISA,AmEx,DinersClub,Discover,JCB,VisaElectron,Solo',
        ]);
    }

    protected function force_ssl()
    {   
        if (is_ssl() === false) {
            wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
            die();
        }
    }

    public function get_integrated_page()
    {
        $args = [
            'meta_key' => 'bookwize_integrated_page_type',
            'meta_value' => 'integrated',
            'post_type' => get_post_types(['public' => true]),
            'post_status' => 'any',
            'posts_per_page' => 1,
            'suppress_filters' => false
        ];
        $posts = @get_posts($args);
        if (isset($posts[0])) {
            return get_permalink($posts[0]->ID);
        }
        return [];
    }
}
