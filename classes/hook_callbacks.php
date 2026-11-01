<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace tool_badgecustomfields;

use core\hook\output\after_standard_main_region_html_generation;
use local_fieldssuite\local\display;
use tool_badgecustomfields\customfield\badge_handler;

/**
 * Hook callbacks of tool_badgecustomfields.
 *
 * @package    tool_badgecustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    /**
     * Shows the values, and the link to edit them, on the badge overview page and on the badge details form.
     *
     * @param after_standard_main_region_html_generation $hook
     */
    public static function after_standard_main_region_html_generation(after_standard_main_region_html_generation $hook): void {
        global $PAGE;
        if (!$PAGE->has_set_url()) {
            return;
        }
        $id = (int)$PAGE->url->get_param('id');
        if (!$id) {
            return;
        }
        if ($PAGE->pagetype === 'badges-overview') {
            display::add_to_page($hook, badge_handler::create(), $id, 'h2', true);
        } else if ($PAGE->pagetype === 'badges-edit' && $PAGE->url->get_param('action') === 'badge') {
            display::add_to_page($hook, badge_handler::create(), $id, 'h2', true);
        }
    }
}
