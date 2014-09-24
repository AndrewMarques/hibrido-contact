<?php
/**
 * Plugin Name: hibrido contact
 * Short Name: hc
 * Plugin URI: http://www.souhibrido.com.br/
 * Description: plugin wodpress para facilitar formulários de contato
 * Version: 0.1.0
 * Author: hibrido
 * Author URI: http://www.souhibrido.com.br/
 * License: MIT
 */

// protection
if ( ! defined('ABSPATH')) {
    die;
}

// text domain
load_plugin_textdomain('hc', false, dirname(plugin_basename(__FILE__)) . '/languages/');

// main class
require_once __DIR__ . '/includes/hc.class.php';

// activation hook
register_activation_hook(__FILE__, array('HC', 'activate'));

// deactivation hook
register_deactivation_hook(__FILE__, array('HC', 'deactivate'));

// algumas diretivas precisamos sempre
HC::always();
