<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://www.phpfusion.com/
+--------------------------------------------------------+
| Filename: forum/index.php
| Author: Core Development Team (coredevs@phpfusion.com)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once __DIR__.'/../../maincore.php';
if (!defined('FORUM_EXIST')) {
    redirect(BASEDIR."error.php?code=404");
}
require_once THEMES.'templates/header.php';
require_once INCLUDES."infusions_include.php";
require_once FORUM_CLASS."autoloader.php";
require_once INFUSIONS."forum/forum_include.php";
include INFUSIONS."forum/templates/templates.php";
// Base theme is 0.06s TOP
\PHPFusion\Locale::setLocale(FORUM_LOCALE);
$info = \PHPFusion\Forums\ForumServer::forum()->getForumInfo();
render_forum($info);
require_once THEMES.'templates/footer.php';
