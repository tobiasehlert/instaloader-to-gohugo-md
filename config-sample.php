<?php

/**
 * A script that changes instaloader export to a gohugo format
 * created by Tobias Lindberg in december 2019
 *
 * This is the config file, where you define your values!
 */


// defining some vars that are used below..

// folder, where instaloader places all the txt and json files
define( 'folder_instaloader', '' );

// folder, where you want the generated .md files to be placed
define( 'folder_dest_mdfile', 'content/' );

// folder, where you want all the images to be copied to
define( 'folder_dest_images', 'content/images/' );

// folder, where hashes of migrated files are located
define( 'folder_dest_hashes', 'hashes/' );

// the timezone that you are located in.. select supported timezone from: https://www.php.net/manual/en/timezones.php
define( 'local_timezone', 'Europe/Stockholm' );

// choose, if you want the finished export to Hugo be in draft state or not
define( 'result_draft_state', false );

// enable, if you want to get debug information during runtime
define( 'debug', false );


?>
