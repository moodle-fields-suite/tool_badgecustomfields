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

use tool_badgecustomfields\customfield\badge_handler;

/**
 * Event observers of tool_badgecustomfields.
 *
 * @package    tool_badgecustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {
    /**
     * Deletes the custom field values of a deleted badge (archived badges keep them).
     *
     * @param \core\event\badge_deleted $event
     */
    public static function badge_deleted(\core\event\badge_deleted $event): void {
        badge_handler::create()->delete_instance((int)$event->objectid);
    }

    /**
     * Moves the values of the badges of a deleted course to the system context.
     *
     * Core archives those badges and turns them into site badges, so their values follow them.
     *
     * @param \core\event\course_deleted $event
     */
    public static function course_deleted(\core\event\course_deleted $event): void {
        global $DB;
        $DB->execute('UPDATE {customfield_data}
                         SET contextid = :systemid
                       WHERE contextid = :contextid AND fieldid IN (
                             SELECT f.id
                               FROM {customfield_field} f
                               JOIN {customfield_category} c ON c.id = f.categoryid
                              WHERE c.component = :component AND c.area = :area)', [
            'systemid' => \context_system::instance()->id,
            'contextid' => $event->contextid,
            'component' => 'tool_badgecustomfields',
            'area' => 'badge',
        ]);
    }
}
