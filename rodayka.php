<?php

/**
 * Plugin Name: Rodayka
 * Plugin URI: https://agencialaf.com
 * Description: Este plugin é parte do site da Rodayka.
 * Version: 1.1.3
 * Author: Ingo Stramm
 * Text Domain: rk
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('RK_DIR', plugin_dir_path(__FILE__));
define('RK_URL', plugin_dir_url(__FILE__));

require_once RK_DIR . 'tgm/tgm.php';
require_once RK_DIR . 'classes/class-post-type.php';
require_once RK_DIR . 'function.php';
require_once RK_DIR . 'post-type.php';
require_once RK_DIR . 'cmb.php';
require_once RK_DIR . 'shortcode.php';
require_once RK_DIR . 'scripts.php';

require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/rodayka/master/info.json',
    __FILE__,
    'rodayka'
);
