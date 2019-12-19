# Welcome to instaloader-to-gohugo-md!

This is repo contains a php-based script, that helps you insert [instaloader](https://instaloader.github.io/)-exports to be used in your [gohugo](https://gohugo.io/)-website (or other site, that can use .md files.

**Instaloader** is a tool to download pictures (or videos) along with their captions and other metadata from Instagram.

**Hugo** is one of the most popular open-source static site generators.

## Install

 1. Install Instaloader to your server.

 2. And run of course your first Instaloader export, so you got the data when using this script:

    `instaloader <THE_ACCOUNT_TO_EXPORT> --login <YOUR_INSTAGRAM_ACCOUNT> --no-profile-pic --no-compress-json --commit-mode`

 3. Run this, where you want this repo to be placed (for example your `~/` home directory):

    `git clone git@github.com:tobiasehlert/instaloader-to-gohugo-md.git`
 
 4. Change into directory you just cloned:

    `cd instaloader-to-gohugo-md/`
 
 5. Create your own config.php file based on the sample file. First copy it and then edit it:

    `cp config-sample.php config.php; vim config.php`
 
 6. Run the script:

    `php convert.php`

Now you should have a content folder, where you can find both the .md files that are generated and one image folder, where the matching images have been added to.

## Crontab
You can schedule both Instaloader and the convert script to be run in crontab.
If you do this, you can also automatically deploy your changes directly to your homepage or so.. depending on how you want!

Good tip is to first run Instaloader and 5 minutes later maybe the convert script. Then you convert the newly created posts automatically pretty easy.

### convert.php in crontab
Example of crontab, which means that the convert.php file gets run every full hour:

`0 * * * * /usr/bin/php convert.php >/dev/null 2>&1`

### Instaloader in crontab
If you want Instaloader to download new picture automatically, you can schedule following in your user.

To run the following command, note that you need an existing session file (saved in */tmp/.instaloader-<YOUR_LINUX_USER>/session-<YOUR_INSTAGRAM_ACCOUNT>*), that you can reuse, to trigger a first run as shown under install, and then this will work just fine.

`instaloader <THE_ACCOUNT_TO_EXPORT> --login <YOUR_INSTAGRAM_ACCOUNT> --no-profile-pic --no-compress-json --commit-mode --fast-update --quiet`

Adjusting your crontab running as you wish with the flags you want to use. This is just an example, as I run it :)

## Maintenance
This repo is maintained by its author [Tobias Lindberg](https://github.com/tobiasehlert) with the help from these awesome [contributors](https://github.com/tobiasehlert/instaloader-to-gohugo-md/blob/master/CONTRIBUTORS.md)..

## License
Coder is licensed under the [LICENSE](https://github.com/tobiasehlert/instaloader-to-gohugo-md/blob/master/LICENSE).

