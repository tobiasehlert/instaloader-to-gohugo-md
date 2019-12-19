<?php

/**
 * A script that changes instaloader export to a gohugo format
 * created by Tobias Lindberg in december 2019
 *
 * This is the convert file, that scans the folders and creates .md files
 * that you then can use in form example gohugo or so..
 */


// loading config file with defined values to the script
if ( file_exists( 'config.php' ) )
	require_once( 'config.php' );
else
{
	echo 'Error: can\'t locate config.php! please create it by copying config-sample.php and setting your custom values.';
	die();
}
// loading functions that are used in the script
if ( file_exists( 'functions.php' ) )
	require_once( 'functions.php' );
else
{
	echo 'Error: can\'t locate functions.php! please check where it went.';
	die();
}


// Now lets begin with the script!

// setting timezone
date_default_timezone_set( local_timezone );

// start calculation of total converted files
$total = 0;

// check if the hash-folder is existing
if ( !file_exists( folder_dest_hashes ) )
	mkdir( folder_dest_hashes, 0755, true );

// get all hash files from hash folder
$hashdata = scandir( folder_dest_hashes );

// scanning folder, where instagram export has been places
$dirdata = scandir( folder_instaloader );

// going through all the files in that folder
foreach ( $dirdata as $key => $value )
{
	// only ork with txt files.. so skipping everything else
	if ( $value != '.' and $value != '..' and $value != 'id' && strpos( $value, '_location' ) === false )
	{
		// extract extension
		$file_tmp = array_reverse( explode( '.', $value ) );
		$file_type = $file_tmp[0]; // filename extension only
		$file_name = $file_tmp[1]; // filename without extension

		if ( $file_type == 'txt' )
		{

			// generating hash from filename
			$hash = md5_file( folder_instaloader.$value );

			// check if this file has been processed earlier, by checking hash
			if ( in_array( $hash.'.txt', $hashdata ) )
			{
				if ( debug )
					print_r( 'md5_file has '.$hash.' exists for '.$value.'.. skipping converting of file!'."\n\r" );
			}
			else
			{
				// resetting matches from previous look..
				$hashtags = array();
				$dates = array();
				$urls = array();

				// getting content of the txt files
				$file = file_get_contents( folder_instaloader . $value );
				$result[$key]['original'] = $file;
	
				// getting date of picture
				if ( preg_match( "/^(\d{4})-(\d{2})-(\d{2})_(\d{2})-(\d{2})-(\d{2})_(.*).txt$/", $value, $dates ) )
				{
					$result[$key]['date_human'] = $dates[1].'-'.$dates[2].'-'.$dates[3].' '.$dates[4].':'.$dates[5].':'.$dates[6];
					$result[$key]['date_yaml'] = date( 'c', strtotime( $result[$key]['date_human'] ) );
				}

				// getting hashtags only from file
				$result[$key]['hashtags'] = array();
				if ( preg_match_all( "/#([^\s]+)/", $file, $hashtags ) ) {
					$result[$key]['hashtags'] = $hashtags[1];
				}

				// getting status update from file, but only until first hashtag
				if ( preg_match( "/(.*)#/U", $file, $urls ) )
					$result[$key]['text'] = trim( $urls[1] );
				if ( ! array_key_exists( 'text', $result[$key] ) )
					$result[$key]['text'] = trim( $file );

				// create a better slug for the posti
				$result[$key]['slug'] = removeAccents( $result[$key]['text'] );
				$result[$key]['slug'] = preg_replace( '/\W+/', '-', strtolower( $result[$key]['slug'] ) );
				$result[$key]['slug'] = trim( $result[$key]['slug'], '-' );

				// generating a new filename to match with images
				$result[$key]['filename'] = $dates[1].'-'.$dates[2].'-'.$dates[3].'-'.$result[$key]['slug'];

				// shorten filename, if it's too long.. else php will throw warning
				if ( strlen( $result[$key]['filename'] ) > 200 )
					$result[$key]['filename'] = substr( $result[$key]['filename'], 0, 200 );

				// create the content to be written to a file
				$content = '
---
title: "'.$result[$key]['text'].'"
date: "'.$result[$key]['date_yaml'].'"
slug: "'.$dates[1].'-'.$dates[2].'/'.$result[$key]['slug'].'"
publishDate: "'.$result[$key]['date_yaml'].'"
draft: '.result_draft_state;
				if ( !empty( $result[$key]['hashtags'] ) )
				{
					$content .= '
tags:';
					foreach ( $result[$key]['hashtags'] as $id => $tag )
						$content .= '
- '.$tag;
				}
				$content .= '
---

{{< figure src="'.folder_dest_images.'/'.$result[$key]['filename'].'.jpg" >}}

### '.$result[$key]['text'].'

';

				// create destination folders, if they don't exist
				if ( !file_exists( folder_dest_mdfile ) )
					mkdir( folder_dest_mdfile, 0755, true );
				if ( !file_exists( folder_dest_images ) )
					mkdir( folder_dest_images, 0755, true );

				// writing the data to an .md file as defined at the top of this file
				file_put_contents( folder_dest_mdfile.$result[$key]['filename'].'.md', $content );
				// copy of images to an image subfolder defined at top of this file
				copy( folder_instaloader.$file_name.'.jpg', folder_dest_images.$result[$key]['filename'].'.jpg' );

				// saving hash file, so that we know this file has been migrated
				file_put_contents( folder_dest_hashes.$hash.'.txt', $result[$key]['filename'] );

				// return info if debug is enabled
				if ( debug )
					print_r( 'Converting of '.$value.' successful!'."\n\r" );

				// count total converted files
				$total++;
			}
		}
	}
}

print_r( 'Script completed! Converted '.$total.' instaloder exports..'."\n\r" );

?>
