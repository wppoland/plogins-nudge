<?php
/**
 * Uninstall cleanup for Nudge.
 *
 * Runs when the plugin is deleted from wp-admin. Removes the options Nudge
 * creates. No custom tables or post meta are created by Nudge.
 *
 * @package Nudge
 */

declare(strict_types=1);

defined('WP_UNINSTALL_PLUGIN') || exit;

/**
 * Remove this plugin's options from the current site.
 */
function nudge_uninstall_site(): void
{
    delete_option('nudge_settings');
    delete_option('nudge_db_version');
}

// Options are per site, so a network uninstall has to visit every site.
if (is_multisite()) {
    foreach (get_sites(['fields' => 'ids', 'number' => 0]) as $nudge_site_id) {
        switch_to_blog((int) $nudge_site_id);
        nudge_uninstall_site();
        restore_current_blog();
    }
    unset($nudge_site_id);
} else {
    nudge_uninstall_site();
}

// The PRO banner's dismissal is stored per user, so it belongs to the
// plugin rather than to the site content. User meta is global, not
// per-site, which is why this uses delete_metadata's \$delete_all rather
// than a loop over the users of one blog.
delete_metadata('user', 0, 'nudge_pro_banner_dismissed', '', true);
