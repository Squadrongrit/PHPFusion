<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: CoreSettings.php
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

namespace PHPFusion\Installer\Lib;

use PHPFusion\Installer\InstallCore;

class CoreSettings {
    /**
     * @param string $table_name
     * @param string $localeset
     *
     * @return array[]|\array[][]|mixed|null
     */
    public static function get_table_rows( $table_name, $localeset = 'English' ) {
        $locale = fusion_get_locale();

        include BASEDIR . 'locale/' . $localeset . '/setup.php';
        include BASEDIR . 'locale/' . $localeset . '/admin/members_email.php';
        include BASEDIR . 'locale/' . $localeset . '/policies.php';

        $siteurl = rtrim( dirname( get_current_url() ), '/' ) . '/';
        $siteurl = str_replace( 'install/', '', $siteurl );
        $url = parse_url( $siteurl );

        $table_settings['settings'] = [
            'insert' => [
                ['settings_name' => 'sitename', 'settings_value' => $locale['setup_1215'],],
                ['settings_name' => 'siteurl', 'settings_value' => $siteurl],
                ['settings_name' => 'site_protocol', 'settings_value' => $url['scheme']],
                ['settings_name' => 'site_host', 'settings_value' => $url['host'],],
                ['settings_name' => 'site_port', 'settings_value' => ($url['port'] ?? ''),],
                ['settings_name' => 'site_path', 'settings_value' => ($url['path'] ?? ''),],
                ['settings_name' => 'site_seo', 'settings_value' => 0],
                ['settings_name' => 'domain_server', 'settings_value' => '',],
                ['settings_name' => 'sitebanner', 'settings_value' => 'images/phpfusion-logo-d.svg'],
                ['settings_name' => 'sitebanner1', 'settings_value' => ''],
                ['settings_name' => 'sitebanner2', 'settings_value' => ''],
                ['settings_name' => 'siteemail', 'settings_value' => ''],//fill in
                ['settings_name' => 'siteusername', 'settings_value' => ''],// fill in
                ['settings_name' => 'siteintro', 'settings_value' => "<div style=\'text-align:center\'>" . $locale['setup_3650'] . "</div>"],
                ['settings_name' => 'description', 'settings_value' => $locale['setup_1216']],
                ['settings_name' => 'keywords', 'settings_value' => 'PHPFusion, CMS, Community, Hosting, Domain, Portal, Open Source, AGPL, PHP, MySQL, HTML, CSS, JS'],
                ['settings_name' => 'footer', 'settings_value' => ''],
                ['settings_name' => 'opening_page', 'settings_value' => 'index.php'],
                ['settings_name' => 'locale', 'settings_value' => $_GET['localeset'] ?? 'English'],
                ['settings_name' => 'enabled_languages', 'settings_value' => $_GET['localeset'] ?? 'English'],
                ['settings_name' => 'theme', 'settings_value' => 'Magazine'],
                ['settings_name' => 'admin_theme', 'settings_value' => 'AdminLTE'],
                ['settings_name' => 'default_search', 'settings_value' => 'all'],
                ['settings_name' => 'exclude_left', 'settings_value' => ''],
                ['settings_name' => 'exclude_upper', 'settings_value' => ''],
                ['settings_name' => 'exclude_lower', 'settings_value' => ''],
                ['settings_name' => 'exclude_aupper', 'settings_value' => ''],
                ['settings_name' => 'exclude_blower', 'settings_value' => ''],
                ['settings_name' => 'exclude_right', 'settings_value' => ''],
                ['settings_name' => 'exclude_user1', 'settings_value' => ''],
                ['settings_name' => 'exclude_user2', 'settings_value' => ''],
                ['settings_name' => 'exclude_user3', 'settings_value' => ''],
                ['settings_name' => 'exclude_user4', 'settings_value' => ''],
                ['settings_name' => 'shortdate', 'settings_value' => '%d.%m.%y',],
                ['settings_name' => 'longdate', 'settings_value' => '%B %d %Y %H:%M:%S',],
                ['settings_name' => 'forumdate', 'settings_value' => '%d-%m-%Y %H:%M',],
                ['settings_name' => 'newsdate', 'settings_value' => '%B %d %Y'],
                ['settings_name' => 'timeoffset', 'settings_value' => 'Europe/London'],
                ['settings_name' => 'week_start', 'settings_value' => 0],
                ['settings_name' => 'enable_registration', 'settings_value' => 1],
                ['settings_name' => 'email_verification', 'settings_value' => 1],
                ['settings_name' => 'admin_activation', 'settings_value' => 0],
                ['settings_name' => 'display_validation', 'settings_value' => 0],
                ['settings_name' => 'enable_deactivation', 'settings_value' => 0],
                ['settings_name' => 'deactivation_period', 'settings_value' => 365],
                ['settings_name' => 'deactivation_response', 'settings_value' => 14],
                ['settings_name' => 'enable_terms', 'settings_value' => 1],
                ['settings_name' => 'thumb_compression', 'settings_value' => 'gd2'],
                ['settings_name' => 'tinymce_enabled', 'settings_value' => 0],
                ['settings_name' => 'smtp_host', 'settings_value' => ''],
                ['settings_name' => 'smtp_port', 'settings_value' => 25],
                ['settings_name' => 'smtp_username', 'settings_value' => ''],
                ['settings_name' => 'smtp_password', 'settings_value' => ''],
                ['settings_name' => 'bad_words_enabled', 'settings_value' => 1],
                ['settings_name' => 'bad_words', 'settings_value' => ''],
                ['settings_name' => 'bad_word_replace', 'settings_value' => '****'],
                ['settings_name' => 'login_method', 'settings_value' => 0],
                ['settings_name' => 'guestposts', 'settings_value' => 0],
                ['settings_name' => 'comments_enabled', 'settings_value' => 1],
                ['settings_name' => 'ratings_enabled', 'settings_value' => 1],
                ['settings_name' => 'hide_userprofiles', 'settings_value' => 0],
                ['settings_name' => 'userthemes', 'settings_value' => 0],
                ['settings_name' => 'flood_interval', 'settings_value' => 15],
                ['settings_name' => 'counter', 'settings_value' => 0],
                ['settings_name' => 'version', 'settings_value' => InstallCore::BUILD_VERSION],
                ['settings_name' => 'maintenance', 'settings_value' => 0],
                ['settings_name' => 'maintenance_message', 'settings_value' => ''],
                ['settings_name' => 'links_per_page', 'settings_value' => 8],
                ['settings_name' => 'links_grouping', 'settings_value' => 0],
                ['settings_name' => 'link_grouping', 'settings_value' => 8],
                ['settings_name' => 'link_bbcode', 'settings_value' => '0',],
                ['settings_name' => 'comments_per_page', 'settings_value' => 10],
                ['settings_name' => 'comments_sorting', 'settings_value' => 'ASC'],
                ['settings_name' => 'comments_avatar', 'settings_value' => 1],
                ['settings_name' => 'avatar_width', 'settings_value' => 500],
                ['settings_name' => 'avatar_height', 'settings_value' => 500],
                ['settings_name' => 'avatar_filesize', 'settings_value' => 1048576],
                ['settings_name' => 'avatar_ratio', 'settings_value' => 0],
                ['settings_name' => 'cronjob_day', 'settings_value' => time()],
                ['settings_name' => 'cronjob_hour', 'settings_value' => time()],
                ['settings_name' => 'flood_autoban', 'settings_value' => 1],
                ['settings_name' => 'visitorcounter_enabled', 'settings_value' => 1],
                ['settings_name' => 'rendertime_enabled', 'settings_value' => 0],
                ['settings_name' => 'maintenance_level', 'settings_value' => USER_LEVEL_ADMIN],
                ['settings_name' => 'deactivation_action', 'settings_value' => 0],
                ['settings_name' => 'captcha', 'settings_value' => 'securimage3'],
                ['settings_name' => 'password_algorithm', 'settings_value' => 'sha256'],
                ['settings_name' => 'username_change', 'settings_value' => 1],
                ['settings_name' => 'username_display', 'settings_value' => 1],
                ['settings_name' => 'recaptcha_public', 'settings_value' => ''],
                ['settings_name' => 'recaptcha_private', 'settings_value' => ''],
                ['settings_name' => 'recaptcha_theme', 'settings_value' => 'light'],
                ['settings_name' => 'recaptcha_type', 'settings_value' => 'text'],
                ['settings_name' => 'recaptcha_score', 'settings_value' => '0.5'],
                ['settings_name' => 'multiple_logins', 'settings_value' => 0],
                ['settings_name' => 'smtp_auth', 'settings_value' => 0],
                ['settings_name' => 'mime_check', 'settings_value' => 1],
                ['settings_name' => 'normalize_seo', 'settings_value' => 0],
                ['settings_name' => 'debug_seo', 'settings_value' => 0],
                ['settings_name' => 'create_og_tags', 'settings_value' => 1],
                ['settings_name' => 'index_url_bbcode', 'settings_value' => 1],
                ['settings_name' => 'index_url_userweb', 'settings_value' => 1],
                ['settings_name' => 'pm_inbox_limit', 'settings_value' => 20],
                ['settings_name' => 'pm_outbox_limit', 'settings_value' => 20],
                ['settings_name' => 'pm_archive_limit', 'settings_value' => 20],
                ['settings_name' => 'pm_email_notify', 'settings_value' => 2],
                ['settings_name' => 'pm_save_sent', 'settings_value' => 2],
                ['settings_name' => 'username_ban', 'settings_value' => ''],
                ['settings_name' => 'database_sessions', 'settings_value' => 0],
                ['settings_name' => 'form_tokens', 'settings_value' => 5],
                ['settings_name' => 'gateway', 'settings_value' => 1],
                ['settings_name' => 'gateway_method', 'settings_value' => 1],
                ['settings_name' => 'devmode', 'settings_value' => 0],
                ['settings_name' => 'update_checker', 'settings_value' => 1],
                ['settings_name' => 'update_last_checked', 'settings_value' => time()],
                ['settings_name' => 'number_delimiter', 'settings_value' => '.'],
                ['settings_name' => 'thousands_separator', 'settings_value' => ','],
                ['settings_name' => 'error_logging_enabled', 'settings_value' => 1],
                ['settings_name' => 'error_logging_method', 'settings_value' => 'database'],
                ['settings_name' => 'auth_login_enabled', 'settings_value' => '1'],
                ['settings_name' => 'auth_login_expiry', 'settings_value' => '300'],
                ['settings_name' => 'auth_login_length', 'settings_value' => '6'],
                ['settings_name' => 'auth_login_attempts', 'settings_value' => '3'],
                ['settings_name' => 'login_session_expiry', 'settings_value' => '43200'],
                ['settings_name' => 'login_session_ext_expiry', 'settings_value' => '86400'],
                ['settings_name' => 'admin_session_expiry', 'settings_value' => '43200'],
                ['settings_name' => 'admin_session_ext_expiry', 'settings_value' => '86400'],
                ['settings_name' => 'password_length', 'settings_value' => '8'],
                ['settings_name' => 'password_char', 'settings_value' => '1'],
                ['settings_name' => 'password_num', 'settings_value' => '1'],
                ['settings_name' => 'password_case', 'settings_value' => '1'],
                ['settings_name' => 'license', 'settings_value' => 'agpl'],
                ['settings_name' => 'license_key', 'settings_value' => ''],
            ]
        ];
        $table_settings['mlt_tables'] = [
            'insert' => [
                [
                    'mlt_rights' => 'SL',
                    'mlt_title'  => $locale['setup_3023'],
                    'mlt_status' => 1
                ],
                [
                    'mlt_rights' => 'CP',
                    'mlt_title'  => $locale['setup_3201'],
                    'mlt_status' => 1
                ],
                [
                    'mlt_rights' => 'ET',
                    'mlt_title'  => $locale['setup_3208'],
                    'mlt_status' => 1
                ],
                [
                    'mlt_rights' => 'PN',
                    'mlt_title'  => $locale['setup_3211'],
                    'mlt_status' => 1
                ],
                [
                    'mlt_rights' => 'TOS',
                    'mlt_title'  => $locale['pol_100'],
                    'mlt_status' => 1
                ]
            ]

        ];
        $table_settings['admin'] = [
            'insert' => [
                [
                    'admin_rights'   => 'AD',
                    'admin_image'    => 'administrator.png',
                    'admin_title'    => $locale['setup_3000'],
                    'admin_link'     => 'administrators.php',
                    'admin_page'     => 2,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'APWR',
                    'admin_image'    => 'adminpass.png',
                    'admin_title'    => $locale['setup_3047'],
                    'admin_link'     => 'admin_reset.php',
                    'admin_page'     => 2,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'SB',
                    'admin_image'    => 'banner.png',
                    'admin_title'    => $locale['setup_3003'],
                    'admin_link'     => 'banners.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'BB',
                    'admin_image'    => 'bbcodes.png',
                    'admin_title'    => $locale['setup_3004'],
                    'admin_link'     => 'bbcodes.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'B',
                    'admin_image'    => 'blacklist.png',
                    'admin_title'    => $locale['setup_3005'],
                    'admin_link'     => 'blacklist.php',
                    'admin_page'     => 2,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'C',
                    'admin_image'    => 'comments.png',
                    'admin_title'    => $locale['setup_3006'],
                    'admin_link'     => 'comments.php',
                    'admin_page'     => 1,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'CP',
                    'admin_image'    => 'c-pages.png',
                    'admin_title'    => $locale['setup_3007'],
                    'admin_link'     => 'custom_pages.php',
                    'admin_page'     => 1,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'DB',
                    'admin_image'    => 'db_backup.png',
                    'admin_title'    => $locale['setup_3008'],
                    'admin_link'     => 'db_backup.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'ERRO',
                    'admin_image'    => 'errors.png',
                    'admin_title'    => $locale['setup_3048'],
                    'admin_link'     => 'errors.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'IM',
                    'admin_image'    => 'images.png',
                    'admin_title'    => $locale['setup_3013'],
                    'admin_link'     => 'images.php',
                    'admin_page'     => 1,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'I',
                    'admin_image'    => 'infusions.png',
                    'admin_title'    => $locale['setup_3014'],
                    'admin_link'     => 'infusions.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'TOS',
                    'admin_image'    => 'policy.png',
                    'admin_title'    => $locale['pol_100'],
                    'admin_link'     => 'policies.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'IP',
                    'admin_image'    => '',
                    'admin_title'    => $locale['setup_3015'],
                    'admin_link'     => 'reserved',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'M',
                    'admin_image'    => 'members.png',
                    'admin_title'    => $locale['setup_3016'],
                    'admin_link'     => 'members.php',
                    'admin_page'     => 2,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'MI',
                    'admin_image'    => 'migration.png',
                    'admin_title'    => $locale['setup_3057'],
                    'admin_link'     => 'migrate.php',
                    'admin_page'     => 2,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'P',
                    'admin_image'    => 'panels.png',
                    'admin_title'    => $locale['setup_3019'],
                    'admin_link'     => 'panels.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'PL',
                    'admin_image'    => 'permalink.png',
                    'admin_title'    => $locale['setup_3052'],
                    'admin_link'     => 'permalink.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'PI',
                    'admin_image'    => 'serverinfo.png',
                    'admin_title'    => $locale['setup_3021'],
                    'admin_link'     => 'serverinfo.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'SL',
                    'admin_image'    => 'sitelinks.png',
                    'admin_title'    => $locale['setup_3023'],
                    'admin_link'     => 'site_links.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'SM',
                    'admin_image'    => 'smileys.png',
                    'admin_title'    => $locale['setup_3024'],
                    'admin_link'     => 'smileys.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'U',
                    'admin_image'    => 'upgrade.png',
                    'admin_title'    => $locale['setup_3026'],
                    'admin_link'     => 'upgrade.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'TS',
                    'admin_image'    => 'theme.png',
                    'admin_title'    => $locale['setup_3056'],
                    'admin_link'     => 'theme.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'UG',
                    'admin_image'    => 'user_groups.png',
                    'admin_title'    => $locale['setup_3027'],
                    'admin_link'     => 'user_groups.php',
                    'admin_page'     => 2,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'S1',
                    'admin_image'    => 'settings.png',
                    'admin_title'    => $locale['setup_3030'],
                    'admin_link'     => 'settings_main.php',
                    'admin_page'     => 4,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'S2',
                    'admin_image'    => 'time.png',
                    'admin_title'    => $locale['setup_3031'],
                    'admin_link'     => 'settings_time.php',
                    'admin_page'     => 4,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'S4',
                    'admin_image'    => 'registration.png',
                    'admin_title'    => $locale['setup_3033'],
                    'admin_link'     => 'settings_registration.php',
                    'admin_page'     => 4,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'S6',
                    'admin_image'    => 'misc.png',
                    'admin_title'    => $locale['setup_3035'],
                    'admin_link'     => 'settings_misc.php',
                    'admin_page'     => 4,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'S7',
                    'admin_image'    => 'pm.png',
                    'admin_title'    => $locale['setup_3036'],
                    'admin_link'     => 'settings_messages.php',
                    'admin_page'     => 4,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'S9',
                    'admin_image'    => 'user_settings.png',
                    'admin_title'    => $locale['setup_3041'],
                    'admin_link'     => 'settings_users.php',
                    'admin_page'     => 4,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'S12',
                    'admin_image'    => 'security.png',
                    'admin_title'    => $locale['setup_3044'],
                    'admin_link'     => 'settings_security.php',
                    'admin_page'     => 4,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'UF',
                    'admin_image'    => 'user_fields.png',
                    'admin_title'    => $locale['setup_3037'],
                    'admin_link'     => 'user_fields.php',
                    'admin_page'     => 2,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'UL',
                    'admin_image'    => 'user_log.png',
                    'admin_title'    => $locale['setup_3049'],
                    'admin_link'     => 'user_log.php',
                    'admin_page'     => 2,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'ROB',
                    'admin_image'    => 'robots.png',
                    'admin_title'    => $locale['setup_3050'],
                    'admin_link'     => 'robots.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'MAIL',
                    'admin_image'    => 'email.png',
                    'admin_title'    => $locale['setup_3800'],
                    'admin_link'     => 'email.php',
                    'admin_page'     => 3,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'LANG',
                    'admin_image'    => 'language.png',
                    'admin_title'    => $locale['setup_3051'],
                    'admin_link'     => 'settings_languages.php',
                    'admin_page'     => 4,
                    'admin_language' => $localeset
                ],
                [
                    'admin_rights'   => 'FM',
                    'admin_image'    => 'file_manager.png',
                    'admin_title'    => $locale['setup_3059'],
                    'admin_link'     => 'file_manager.php',
                    'admin_page'     => 1,
                    'admin_language' => $localeset
                ]
            ]
        ];
        $table_settings['bbcodes'] = [
            'insert' => [
                [
                    'bbcode_name'  => 'smiley',
                    'bbcode_order' => 1
                ],
                [
                    'bbcode_name'  => 'b',
                    'bbcode_order' => 2
                ],
                [
                    'bbcode_name'  => 'i',
                    'bbcode_order' => 3
                ],
                [
                    'bbcode_name'  => 'u',
                    'bbcode_order' => 4
                ],
                [
                    'bbcode_name'  => 'url',
                    'bbcode_order' => 5
                ],
                [
                    'bbcode_name'  => 'mail',
                    'bbcode_order' => 6
                ],
                [
                    'bbcode_name'  => 'img',
                    'bbcode_order' => 7
                ],
                [
                    'bbcode_name'  => 'center',
                    'bbcode_order' => 8
                ],
                [
                    'bbcode_name'  => 'small',
                    'bbcode_order' => 9
                ],
                [
                    'bbcode_name'  => 'code',
                    'bbcode_order' => 10
                ],
                [
                    'bbcode_name'  => 'quote',
                    'bbcode_order' => 11
                ]
            ]

        ];
        $table_settings['smileys'] = [
            'insert' => [
                [
                    'smiley_code'  => ':)',
                    'smiley_image' => 'smile.svg',
                    'smiley_text'  => $locale['setup_3620']
                ],
                [
                    'smiley_code'  => ';)',
                    'smiley_image' => 'wink.svg',
                    'smiley_text'  => $locale['setup_3621']
                ],
                [
                    'smiley_code'  => ':(',
                    'smiley_image' => 'sad.svg',
                    'smiley_text'  => $locale['setup_3622']
                ],
                [
                    'smiley_code'  => ':|',
                    'smiley_image' => 'frown.svg',
                    'smiley_text'  => $locale['setup_3623']
                ],
                [
                    'smiley_code'  => ':o',
                    'smiley_image' => 'shock.svg',
                    'smiley_text'  => $locale['setup_3624']
                ],
                [
                    'smiley_code'  => ':P',
                    'smiley_image' => 'pfft.svg',
                    'smiley_text'  => $locale['setup_3625']
                ],
                [
                    'smiley_code'  => 'B)',
                    'smiley_image' => 'cool.svg',
                    'smiley_text'  => $locale['setup_3626']
                ],
                [
                    'smiley_code'  => ':D',
                    'smiley_image' => 'grin.svg',
                    'smiley_text'  => $locale['setup_3627']
                ],
                [
                    'smiley_code'  => ':@',
                    'smiley_image' => 'angry.svg',
                    'smiley_text'  => $locale['setup_3628']
                ],
                [
                    'smiley_code'  => '(y)',
                    'smiley_image' => 'like.svg',
                    'smiley_text'  => $locale['setup_3629']
                ]
            ]

        ];
        $table_settings['panels'] = [
            'insert' => [
                // Panel Side Left Arrangements

                // LEFT
                [
                    'panel_name'        => $locale['setup_3400'],
                    'panel_filename'    => 'css_navigation_panel',
                    'panel_content'     => '',
                    'panel_side'        => 1,
                    'panel_order'       => 1,
                    'panel_type'        => 'file',
                    'panel_access'      => 0,
                    'panel_display'     => 1,
                    'panel_status'      => 1,
                    'panel_url_list'    => '',
                    'panel_restriction' => 3
                ],
                [
                    'panel_name'        => $locale['setup_3408'],
                    'panel_filename'    => 'rss_feeds_panel',
                    'panel_content'     => '',
                    'panel_side'        => 1,
                    'panel_order'       => 2,
                    'panel_type'        => 'file',
                    'panel_access'      => 0,
                    'panel_display'     => 0,
                    'panel_status'      => 1,
                    'panel_url_list'    => '',
                    'panel_restriction' => 2
                ],
                [
                    'panel_name'        => $locale['setup_3401'],
                    'panel_filename'    => 'online_users_panel',
                    'panel_content'     => '',
                    'panel_side'        => 1,
                    'panel_order'       => 3,
                    'panel_type'        => 'file',
                    'panel_access'      => 0,
                    'panel_display'     => 1,
                    'panel_status'      => 1,
                    'panel_url_list'    => '',
                    'panel_restriction' => 3
                ],
                // U_CENTER
                [
                    'panel_name'        => $locale['setup_3404'],
                    'panel_filename'    => 'welcome_message_panel',
                    'panel_content'     => '',
                    'panel_side'        => 2,
                    'panel_order'       => 1,
                    'panel_type'        => 'file',
                    'panel_access'      => 0,
                    'panel_display'     => 0,
                    'panel_status'      => 1,
                    'panel_url_list'    => '',
                    'panel_restriction' => 2
                ],
                // RIGHT
                [
                    'panel_name'        => $locale['setup_3406'],
                    'panel_filename'    => 'user_info_panel',
                    'panel_content'     => '',
                    'panel_side'        => 4,
                    'panel_order'       => 1,
                    'panel_type'        => 'file',
                    'panel_access'      => 0,
                    'panel_display'     => 1,
                    'panel_status'      => 1,
                    'panel_url_list'    => '',
                    'panel_restriction' => 3
                ]
            ]
        ];
        $table_settings['user_field_cats'] = [
            'insert' => [
                [
                    'field_cat_id'    => 1,
                    'field_cat_name'  => $locale['setup_3640'],
                    'field_parent'    => 0,
                    'field_cat_db'    => 'users',
                    'field_cat_index' => 'user_id',
                    'field_cat_class' => 'fa fa-user',
                    'field_cat_order' => 1
                ],

                [
                    'field_cat_id'    => 2,
                    'field_cat_name'  => $locale['setup_3641'],
                    'field_parent'    => 1,
                    'field_cat_db'    => '',
                    'field_cat_index' => '',
                    'field_cat_class' => 'fa fa-user',
                    'field_cat_order' => 1
                ],
                [
                    'field_cat_id'    => 3,
                    'field_cat_name'  => $locale['setup_3642'],
                    'field_parent'    => 1,
                    'field_cat_db'    => '',
                    'field_cat_index' => '',
                    'field_cat_class' => 'fa fa-user',
                    'field_cat_order' => 2
                ],
                [
                    'field_cat_id'    => 4,
                    'field_cat_name'  => $locale['setup_3643'],
                    'field_parent'    => 1,
                    'field_cat_db'    => '',
                    'field_cat_index' => '',
                    'field_cat_class' => 'fa fa-user',
                    'field_cat_order' => 3
                ],
                [
                    'field_cat_id'    => 5,
                    'field_cat_name'  => $locale['setup_3644'],
                    'field_parent'    => 1,
                    'field_cat_db'    => '',
                    'field_cat_index' => '',
                    'field_cat_class' => 'fa fa-user',
                    'field_cat_order' => 4
                ],
                [
                    'field_cat_id'    => 6,
                    'field_cat_name'  => $locale['setup_3645'],
                    'field_parent'    => 1,
                    'field_cat_db'    => '',
                    'field_cat_index' => '',
                    'field_cat_class' => 'fa fa-user',
                    'field_cat_order' => 5
                ]
            ]
        ];
        $table_settings['user_fields'] = [
            'insert' => [
                [
                    'field_name'     => 'user_birthdate',
                    'field_title'    => $locale['uf_birthdate'],
                    'field_cat'      => 3,
                    'field_type'     => 'file',
                    'field_required' => 0,
                    'field_order'    => 2,
                    'field_default'  => '1900-01-01',
                    'field_options'  => '',
                    'field_error'    => '',
                    'field_config'   => ''
                ],
                [
                    'field_name'     => 'user_location',
                    'field_title'    => $locale['uf_location'],
                    'field_cat'      => 3,
                    'field_type'     => 'file',
                    'field_required' => 0,
                    'field_order'    => 2,
                    'field_default'  => '',
                    'field_options'  => '',
                    'field_error'    => '',
                    'field_config'   => ''
                ],
                [
                    'field_name'     => 'user_skype',
                    'field_title'    => $locale['uf_skype'],
                    'field_cat'      => 2,
                    'field_type'     => 'file',
                    'field_required' => 0,
                    'field_order'    => 1,
                    'field_default'  => '',
                    'field_options'  => '',
                    'field_error'    => '',
                    'field_config'   => ''
                ],
                [
                    'field_name'     => 'user_icq',
                    'field_title'    => $locale['uf_icq'],
                    'field_cat'      => 2,
                    'field_type'     => 'file',
                    'field_required' => 0,
                    'field_order'    => 3,
                    'field_default'  => '',
                    'field_options'  => '',
                    'field_error'    => '',
                    'field_config'   => ''
                ],
                [
                    'field_name'     => 'user_web',
                    'field_title'    => $locale['uf_web'],
                    'field_cat'      => 3,
                    'field_type'     => 'file',
                    'field_required' => 0,
                    'field_order'    => 3,
                    'field_default'  => '',
                    'field_options'  => '',
                    'field_error'    => '',
                    'field_config'   => ''
                ],
                [
                    'field_name'     => 'user_timezone',
                    'field_title'    => $locale['uf_timezone'],
                    'field_cat'      => 4,
                    'field_type'     => 'file',
                    'field_required' => 0,
                    'field_order'    => 1,
                    'field_default'  => '',
                    'field_options'  => '',
                    'field_error'    => '',
                    'field_config'   => ''
                ],
                [
                    'field_name'     => 'user_theme',
                    'field_title'    => $locale['uf_theme'],
                    'field_cat'      => 4,
                    'field_type'     => 'file',
                    'field_required' => 0,
                    'field_order'    => 2,
                    'field_default'  => '',
                    'field_options'  => '',
                    'field_error'    => '',
                    'field_config'   => ''
                ],
                [
                    'field_name'     => 'user_sig',
                    'field_title'    => $locale['uf_sig'],
                    'field_cat'      => 4,
                    'field_type'     => 'file',
                    'field_required' => 0,
                    'field_order'    => 3,
                    'field_default'  => '',
                    'field_options'  => '',
                    'field_error'    => '',
                    'field_config'   => ''
                ]
            ]
        ];
        $table_settings['site_links'] = [
            'insert' => [
                [
                    'link_name'       => $locale['setup_3300'],
                    'link_cat'        => '0',
                    'link_icon'       => '',
                    'link_url'        => 'index.php',
                    'link_visibility' => '0',
                    'link_position'   => '2',
                    'link_status'     => '1',
                    'link_window'     => '0',
                    'link_order'      => '1',
                    'link_language'   => $localeset,
                ],
                [
                    'link_name'       => $locale['setup_3305'],
                    'link_cat'        => '0',
                    'link_icon'       => '',
                    'link_url'        => 'contact.php',
                    'link_visibility' => '0',
                    'link_position'   => '3',
                    'link_status'     => '1',
                    'link_window'     => '0',
                    'link_order'      => '8',
                    'link_language'   => $localeset,
                ],
                [
                    'link_name'       => $locale['setup_3309'],
                    'link_cat'        => '0',
                    'link_icon'       => '',
                    'link_url'        => 'search.php',
                    'link_visibility' => '0',
                    'link_position'   => '1',
                    'link_status'     => '1',
                    'link_window'     => '0',
                    'link_order'      => '10',
                    'link_language'   => $localeset,
                ]
            ]
        ];
        $table_settings['email_templates'] = [
            'insert' => [
                [
                    'template_key'          => 'PM',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['setup_3801'],
                    'template_subject'      => $locale['setup_3802'],
                    'template_content'      => $locale['setup_3803'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                [
                    'template_key'          => 'POST',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['setup_3804'],
                    'template_subject'      => $locale['setup_3805'],
                    'template_content'      => $locale['setup_3806'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                [
                    'template_key'          => 'CONTACT',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['setup_3807'],
                    'template_subject'      => $locale['setup_3808'],
                    'template_content'      => $locale['setup_3809'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                [
                    'template_key'          => 'L_2FA', // login group-2fa
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_2fa_name'],
                    'template_subject'      => $locale['email_2fa_subject'],
                    'template_content'      => $locale['email_2fa_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                // Created by admin
                [
                    'template_key'          => 'U_CREATE',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_create_name'],
                    'template_subject'      => $locale['email_create_subject'],
                    'template_content'      => $locale['email_create_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                // User registration verification
                [
                    'template_key'          => 'U_VERIFY',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_verify_name'],
                    'template_subject'      => $locale['email_verify_subject'],
                    'template_content'      => $locale['email_verify_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                // Confirmation account activated by user - post verification
                [
                    'template_key'          => 'U_ACTIVE',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_activate_name'],
                    'template_subject'      => $locale['email_activate_subject'],
                    'template_content'      => $locale['email_activate_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                // Recover password email notification
                [
                    'template_key'          => 'U_LOSTP',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_pass_name'],
                    'template_subject'      => $locale['email_pass_subject'],
                    'template_content'      => $locale['email_pass_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                // Send password email notification
                [
                    'template_key'          => 'U_SENDP',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_pass_name'],
                    'template_subject'      => $locale['email_pass_subject'],
                    'template_content'      => $locale['email_pass_notify'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                // Email change request confirmation
                [
                    'template_key'          => 'U_EMAIL',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_change_name'],
                    'template_subject'      => $locale['email_change_subject'],
                    'template_content'      => $locale['email_change_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                // Password change request confirmation
                [
                    'template_key'          => 'U_PASS',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_passchange_name'],
                    'template_subject'      => $locale['email_passchange_subject'],
                    'template_content'      => $locale['email_passchange_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                [
                    'template_key'          => 'U_SECBAN',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_secban_name'],
                    'template_subject'      => $locale['email_secban_subject'],
                    'template_content'      => $locale['email_secban_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                [
                    'template_key'          => 'U_REACTIVATED',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_reactivated_name'],
                    'template_subject'      => $locale['email_reactivated_subject'],
                    'template_content'      => $locale['email_reactivated_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
                [
                    'template_key'          => 'U_UNSUSPEND',
                    'template_format'       => 'html',
                    'template_active'       => '0',
                    'template_name'         => $locale['email_unsuspend_name'],
                    'template_subject'      => $locale['email_unsuspend_subject'],
                    'template_content'      => $locale['email_unsuspend_message'],
                    'template_sender_name'  => '',
                    'template_sender_email' => '',
                    'template_language'     => $localeset
                ],
            ]
        ];
        $table_settings['policies'] = [
            'insert' => [
                [
                    'policy_name'     => $locale['pol_200'],
                    'policy_content'  => $locale['pol_202'],
                    'policy_date'     => time(),
                    'policy_language' => $localeset
                ],
                [
                    'policy_name'     => $locale['pol_300'],
                    'policy_content'  => $locale['pol_302'],
                    'policy_date'     => time(),
                    'policy_language' => $localeset
                ],
                [
                    'policy_name'     => $locale['pol_400'],
                    'policy_content'  => $locale['pol_402'],
                    'policy_date'     => time(),
                    'policy_language' => $localeset
                ],
                [
                    'policy_name'     => $locale['pol_500'],
                    'policy_content'  => $locale['pol_502'],
                    'policy_date'     => time(),
                    'policy_language' => $localeset
                ],
                [
                    'policy_name'     => $locale['pol_600'],
                    'policy_content'  => $locale['pol_602'],
                    'policy_date'     => time(),
                    'policy_language' => $localeset
                ]
            ]
        ];

        return $table_settings[$table_name] ?? NULL;
    }
}
