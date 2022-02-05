<?php

// SITE PRESETS
define('SITE_ROOT', dirname(__FILE__, 3)); // DO NOT CHANGE
define('SITE_URL', 'http://'.$_SERVER['SERVER_NAME']); // DO NOT CHANGE

// Website Name
define('SITE_NAME', 'Virty');

// Website Description
define('SITE_DESC', 'Virty ');

/**
 * Folder name should be defined starting with the "/" (slash)
 *
 * If you do not plan on having it in a subdomain,
 * keep '' empty without a "/" (slash)
 * example: define('SUB_DIR', '');
 */
define('SUB_DIR', '/panel');

// Loader link // From Root folder
define('LOADER_URL', SITE_ROOT.'/x.exe');


// Discord Webhooks
define('INVITE_WEBHOOK', 'https://discord.com/api/webhooks/');
define('REGISER_WEBHOOK', 'https://discord.com/api/webhooks/');
define('ADMIN_WEBHOOK', 'https://discord.com/api/webhooks/');
define('RESELLER_WEBHOOK', 'https://discord.com/api/webhooks/');
