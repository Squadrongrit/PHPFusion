<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: infusion_db.php
| Author: Core Development Team
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
defined('IN_FUSION') || exit;

if (!defined('DB_POLL_VOTES')) {
    define("DB_POLL_VOTES", DB_PREFIX."poll_votes");
}
if (!defined('DB_POLLS')) {
    define("DB_POLLS", DB_PREFIX."polls");
}

if (!defined("POLLS_LOCALE")) {
    if (file_exists(INFUSIONS."member_poll_panel/locale/".LOCALESET."polls.php")) {
        define("POLLS_LOCALE", INFUSIONS."member_poll_panel/locale/".LOCALESET."polls.php");
    } else {
        define("POLLS_LOCALE", INFUSIONS."member_poll_panel/locale/English/polls.php");
    }
}

\PHPFusion\Admins::getInstance()->setAdminPageIcons("PO", "<i class='admin-ico fa fa-fw fa-bar-chart'></i>");

if (defined('MEMBER_POLL_PANEL_EXISTS')) {
    function polls_cron_job24h_users_data($data) {
        dbquery("DELETE FROM ".DB_POLL_VOTES." WHERE vote_user=:user_id", [':user_id' => $data['user_id']]);
    }

    /**
     * @uses polls_cron_job24h_users_data()
     */
    fusion_add_hook('cron_job24h_users_data', 'polls_cron_job24h_users_data');
}
