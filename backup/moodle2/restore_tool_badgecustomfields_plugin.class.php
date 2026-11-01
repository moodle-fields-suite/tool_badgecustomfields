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
require_once($CFG->dirroot . '/backup/moodle2/restore_tool_plugin.class.php');

/**
 * Restore of the custom field values of the course badges.
 *
 * @package    tool_badgecustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_tool_badgecustomfields_plugin extends restore_tool_plugin {
    /** @var string Item name of the values kept until the badges are restored. */
    const ITEMNAME = 'tool_badgecustomfields_value';

    /**
     * Paths of the values of the badges.
     *
     * @return restore_path_element[]
     */
    protected function define_course_plugin_structure() {
        return [new restore_path_element('badgevalue', $this->get_pathfor('/badgevalues/badgevalue'))];
    }

    /**
     * Keeps a value until the end of the restore: badges are restored by the final task, after the course.
     *
     * @param array|object $data
     */
    public function process_badgevalue($data) {
        $data = (array)$data;
        restore_dbops::set_backup_ids_record($this->get_restoreid(), self::ITEMNAME, $data['id'], 0, null, $data);
    }

    /**
     * Saves the kept values for the restored badges.
     */
    public function after_restore_course() {
        global $DB;
        if (!$this->get_setting_value('badges')) {
            return;
        }
        $handler = \tool_badgecustomfields\customfield\badge_handler::create();
        $records = $DB->get_recordset('backup_ids_temp', [
            'backupid' => $this->get_restoreid(),
            'itemname' => self::ITEMNAME,
        ], 'itemid', 'id, itemid, info');
        foreach ($records as $record) {
            $data = (array)backup_controller_dbops::decode_backup_temp_info($record->info);
            $badgeid = $this->get_mappingid('badge', $data['badgeid']);
            if ($badgeid) {
                $handler->restore_value((int)$badgeid, $data);
            }
        }
        $records->close();
    }
}
