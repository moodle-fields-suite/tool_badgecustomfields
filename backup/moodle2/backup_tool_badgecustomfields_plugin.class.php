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

defined('MOODLE_INTERNAL') || die();

global $CFG;
// Core loads the base class of local plugins only.
require_once($CFG->dirroot . '/backup/moodle2/backup_tool_plugin.class.php');

/**
 * Backup of the custom field values of the course badges.
 *
 * @package    tool_badgecustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_tool_badgecustomfields_plugin extends backup_tool_plugin {
    /**
     * Adds the values of the badges of the course, when the backup includes badges.
     *
     * The badge structure of core has no plugin connection point, so the values hang from the course,
     * each one with the id of its badge.
     *
     * @return backup_plugin_element
     */
    protected function define_course_plugin_structure() {
        global $CFG, $DB;
        require_once($CFG->libdir . '/badgeslib.php');

        $plugin = $this->get_plugin_element();
        $wrapper = new backup_nested_element($this->get_recommended_name());
        $values = new backup_nested_element('badgevalues');
        $value = new backup_nested_element('badgevalue', ['id'], [
            'badgeid', 'shortname', 'type', 'value', 'valueformat', 'valuetrust',
        ]);
        $plugin->add_child($wrapper);
        $wrapper->add_child($values);
        $values->add_child($value);

        if (!$this->get_setting_value('badges')) {
            return $plugin;
        }

        $handler = \tool_badgecustomfields\customfield\badge_handler::create();
        $rows = [];
        $badgeids = $DB->get_fieldset_select('badge', 'id', 'courseid = :courseid AND status <> :archived', [
            'courseid' => $this->task->get_courseid(),
            'archived' => BADGE_STATUS_ARCHIVED,
        ]);
        foreach ($badgeids as $badgeid) {
            foreach ($handler->get_instance_data_for_backup((int)$badgeid) as $row) {
                $rows[] = ['badgeid' => $badgeid] + $row;
            }
        }
        $value->set_source_array($rows);

        return $plugin;
    }
}
