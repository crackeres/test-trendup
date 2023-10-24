<?php

class Trendup_Activate {

  public static function activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'transactions';
    $charset_collate = $wpdb->get_charset_collate();

    $wpdb->query("CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        invId INT NOT NULL,
        outSum DECIMAL(10, 2) NOT NULL,
        signatureValue VARCHAR(255) NOT NULL,
        transactionField VARCHAR(255) NOT NULL,
        linkField TEXT NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;");
}

  public static function Copy_Template_File() {
    $theme_directory = get_template_directory();
    $template_directory = TRENDUP_PLUGIN_DIR . 'templates/';

    $theme_template_path = $theme_directory . '/custom-template.php';

    if (!file_exists($theme_template_path)) {
      $template_content = file_get_contents($template_directory . 'custom-template.php');
      file_put_contents($theme_template_path, $template_content);
    }
  }

}