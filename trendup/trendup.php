<?php
/**
 * Plugin Name: TrendUp
 * Description: Тестовое задание
 * Version: 1.0
 * Author: Cracker
 * Text Domain: trendup
 * Domain Path: /languages
 */

 defined( 'ABSPATH' ) or exit;
 define( 'TRENDUP_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
 define( 'TRENDUP_PLUGIN_URL', plugin_dir_url( __FILE__ ));

 function trendup_activate(){
  require_once TRENDUP_PLUGIN_DIR . 'includes/class-trendup-activate.php';
  Trendup_Activate::activate();
  $trendup_activator = new Trendup_Activate();
  $trendup_activator->Copy_Template_File();
  $trendup_activator->activate();
 }

 register_activation_hook( __FILE__, 'trendup_activate' );