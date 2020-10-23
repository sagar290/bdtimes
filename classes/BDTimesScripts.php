<?php
namespace BDTAP;


if (!class_exists('BDTimesScripts')) {

    class BDTimesScripts
    {

        public $register_styles = [];
        public $register_scripts = [];

        public function __construct()
        {
            $this->register_styles();
            $this->register_scripts();
            // add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'register'));
            // add_action('admin_menu', array($this, 'register_menu'));
            // dd("dhukche");
        }

        public function register_styles()
        {
            $this->register_styles = [
                // [
                //     'id' => EHAAT_PREFIX . '-slick-carousel-main',
                //     'uri' => 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css',
                //     'deps' => [],
                //     'version' => '1.8.1',
                //     'media' => "all",
                //     'enqueue' => true,
                // ],
            ];
        }

        public function register_scripts()
        {
            // wp_deregister_script('jquery'); // register existing jquery
            $this->register_scripts = [
                [
                    'id' => BDTAP_PREFIX . '-bdtimes-yt',
                    'uri' => 'https://www.youtube.com/iframe_api',
                    'deps' => ['jquery'],
                    'version' => '1.0',
                    'footer' => true,
                    'enqueue' => true,
                ],
                [
                    'id' => BDTAP_PREFIX . '-bdtimes-autoplay',
                    'uri' => BDTAP_FILES_URI . '/js/bdtimes-autoplay.js',
                    'deps' => ['jquery', BDTAP_PREFIX . '-bdtimes-yt'],
                    'version' => filemtime(BDTAP_FILES_DIR . '/js/bdtimes-autoplay.js'),
                    'footer' => true,
                    'enqueue' => true,
                ],
            ];
        }

        public function register($hook_suffix)
        {

            // style
            foreach ($this->register_styles as $key => $style) {
                wp_register_style(
                    $style['id'], $style['uri'],
                    $style['deps'],
                    $style['version'],
                    $style['media']
                );
                if ($style['enqueue']) {
                    wp_enqueue_style($style['id']);
                }
            }

            // script
            // dd("dhukche");
            foreach ($this->register_scripts as $key => $script) {
                // dd("dhukche");
                wp_register_script(
                    $script['id'], $script['uri'],
                    $script['deps'],
                    $script['version'],
                    $script['footer']
                );
                if ($script['enqueue']) {
                    wp_enqueue_script($script['id']);
                }
            }

        }

    }

}
