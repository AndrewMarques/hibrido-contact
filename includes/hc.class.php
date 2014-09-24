<?php

class HC
{
    /**
     * @access private
     * @var string
     * @static
     */
    private static $version = '0.1.0';

    /**
     * @access private
     * @var array
     * @static
     */
    private static $ignoredFields = array('nonce', 'action');

    /**
     * @access public
     * @static
     * @return void
     */
    public static function always()
    {
        // make de action
        add_action('wp_ajax_hc', array(__CLASS__, 'respond'));
        add_action('wp_ajax_nopriv_hc', array(__CLASS__, 'respond'));
    }

    /**
     * @access public
     * @static
     * @return void
     */
    public static function activate()
    {
        $timestamp = wp_next_scheduled('hc_clean_tmp');

        if ($timestamp == false) {
            wp_schedule_event(time(), 'daily', 'hc_clean_tmp');
        }
    }

    /**
     * @access public
     * @static
     * @return void
     */
    public static function deactivate()
    {
        wp_clear_scheduled_hook('hc_clean_tmp');
    }

    /**
     * @access public
     * @static
     * @return void
     */
    public static function cleanTmp()
    {
        $tmpdir = wp_upload_dir();
        $tmpdir = $tmpdir['basedir'] .'/hc-tmp';

        if (is_dir($tmpdir)) {
            $glob = array_merge(
                array(),
                glob($tmpdir .'/*'),
                glob($tmpdir .'/**/*')
            );

            array_map('unlink', $glob);
        }
    }

    /**
     * @access public
     * @static
     * @return void
     */
    public static function init()
    {
        // scripts
        add_action('wp_enqueue_scripts', array(__CLASS__, 'scripts'));
    }

    /**
     * @access public
     * @static
     * @return void
     */
    public static function scripts()
    {
        wp_enqueue_script('hc-main', plugin_dir_url(__DIR__) . 'assets/js/main.js', array('jquery-form'), static::$version, true);

        wp_localize_script('hc-main', 'hc', array(
            'action'      => 'hc',
            'nonce'       => wp_create_nonce('hc-nonce'),
            'loadingText' => __('Enviando ...', 'hc')
        ));
    }

    /**
     * @access public
     * @static
     * @return void
     */
    public static function respond()
    {
        if ( ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'hc-nonce')) {
            _e('Nonce incorreto', 'hc');
            die;
        }

        $post = apply_filters('hc-mail-post', $_POST);

        $to = apply_filters('hc-mail-to', get_option('admin_email'));

        if ( ! empty($post[__('assunto', 'hc')])) {
            $defaultSubject = $post[__('assunto', 'hc')];
        } else {
            $defaultSubject = __('Contato enviado pelo site', 'hc');
        }

        $subject = apply_filters('hc-mail-subject', $defaultSubject);

        $defaultMessage = '';
        foreach ($post as $field => $value) {
            if (in_array($field, static::$ignoredFields)) {
                continue;
            }

            $defaultMessage .= ucfirst($field) .': '. $value ."\n\r";
        }
        $defaultMessage = rtrim($defaultMessage);

        $message = apply_filters('hc-mail-message', $defaultMessage, $post);

        $from = 'From: '. get_option('blogname') .' <' . get_option('admin_email') .'>';
        $from = apply_filters('hc-mail-from', $from);

        $headers = array($from);

        if (isset($post[__('email', 'hc')])) {
            $replyTo = $post[__('email', 'hc')];

            if (isset($post[__('nome', 'hc')])) {
                $replyTo = $post[__('nome', 'hc')] .' <'. $replyTo .'>';
            }

            $replyTo = 'Reply-to: ' . $replyTo;
            $replyTo = apply_filters('hc-mail-reply-to', $replyTo);

            $headers[] = $replyTo;
        }

        $headers = apply_filters('hc-mail-headers', $headers);

        // attachments
        $attchs = array();

        if ( ! empty($_FILES)) {
            $tmpdir = wp_upload_dir();
            $tmpdir = $tmpdir['basedir'] .'/hc-tmp/';

            if ( ! is_dir($tmpdir)) {
                $oldmask = umask(0);

                if ( ! mkdir($tmpdir)) {
                    die(json_encode(array(
                        'success' => false,
                        'message' => __('Ocorreu um erro durante o envio', 'hc')
                    )));
                }

                umask($oldmask);
            }

            foreach ($_FILES as $key => $file) {
                if ( ! is_array($file['name'])) {
                    foreach ($file as $k => $v) {
                        $file[$k] = (array) $v;
                    }
                }

                foreach ($file['error'] as $_key => $error) {
                    if ($error != UPLOAD_ERR_OK) {
                        continue;
                    }

                    $tmp_name = $file["tmp_name"][$_key];
                    $name = $file["name"][$_key];
                    $path = $tmpdir .'/'. $name;
                    move_uploaded_file($tmp_name, $path);

                    $attchs[] = $path;
                }
            }
        }

        if (wp_mail($to, $subject, $message, $headers, $attchs)) {
            $response = array(
                'success' => true,
                'message' => __('Enviado com sucesso', 'hc')
            );
        } else {
            $response = array(
                'success' => false,
                'message' => __('Ocorreu um erro durante o envio', 'hc')
            );
        }

        echo json_encode($response);

        // sempre terminamos o script ajax
        die;
    }
}
