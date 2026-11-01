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

use core_customfield\field_controller;
use local_fieldssuite\external\get_values;
use local_fieldssuite\local\display;
use tool_badgecustomfields\customfield\badge_handler;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/badgeslib.php');
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

/**
 * Tests for the badge custom field area.
 *
 * @package    tool_badgecustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \tool_badgecustomfields\customfield\badge_handler
 * @covers     \tool_badgecustomfields\observer
 * @covers     \backup_tool_badgecustomfields_plugin
 * @covers     \restore_tool_badgecustomfields_plugin
 */
final class plugin_test extends \advanced_testcase {
    /**
     * Creates a text field of the badge area.
     *
     * @param string $shortname
     * @return field_controller
     */
    private function create_field(string $shortname): field_controller {
        /** @var \core_customfield_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_customfield');
        $category = $generator->create_category(['component' => 'tool_badgecustomfields', 'area' => 'badge']);
        return $generator->create_field([
            'categoryid' => $category->get('id'),
            'shortname' => $shortname,
            'name' => ucfirst($shortname),
            'type' => 'text',
        ]);
    }

    /**
     * Creates a badge.
     *
     * @param array $record Badge properties.
     * @return \core_badges\badge
     */
    private function create_badge(array $record = []): \core_badges\badge {
        /** @var \core_badges_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_badges');
        return $generator->create_badge($record + ['status' => BADGE_STATUS_ACTIVE]);
    }

    /**
     * Site badges live in the system context and course badges in their course.
     */
    public function test_instance_info(): void {
        $this->resetAfterTest();
        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $site = $this->create_badge(['name' => 'Site badge']);
        $coursebadge = $this->create_badge(['name' => 'Course badge', 'type' => BADGE_TYPE_COURSE, 'courseid' => $course->id]);
        $handler = badge_handler::create();

        $info = $handler->get_instance_info($site->id);
        $this->assertSame('Site badge', $info->name);
        $this->assertEquals(\context_system::instance()->id, $info->context->id);
        $this->assertEquals($site->id, $info->url->get_param('id'));

        $coursecontext = \context_course::instance($course->id);
        $this->assertEquals($coursecontext->id, $handler->get_instance_info($coursebadge->id)->context->id);
        $this->assertEquals($coursecontext->id, $handler->get_instance_context($coursebadge->id)->id);

        $this->assertNull($handler->get_instance_info(999999));
        $this->assertEquals(\context_system::instance()->id, $handler->get_instance_context(999999)->id);
    }

    /**
     * Users who cannot manage badges see only the badges available to earn; nobody sees archived badges.
     */
    public function test_badge_access(): void {
        $this->resetAfterTest();
        $this->setAdminUser();
        $active = $this->create_badge();
        $inactive = $this->create_badge(['status' => BADGE_STATUS_INACTIVE]);
        $archived = $this->create_badge(['status' => BADGE_STATUS_ARCHIVED]);
        $handler = badge_handler::create();
        $this->assertNotNull($handler->get_instance_info($inactive->id));
        $this->assertNull($handler->get_instance_info($archived->id));

        $this->setUser($this->getDataGenerator()->create_user());
        $this->assertNotNull($handler->get_instance_info($active->id));
        $this->assertNull($handler->get_instance_info($inactive->id));
    }

    /**
     * Values are saved in the badge context, shown with the edit link and returned by the web service.
     */
    public function test_values(): void {
        $this->resetAfterTest();
        $this->setAdminUser();
        $badge = $this->create_badge();
        $this->create_field('hours');
        $handler = badge_handler::create();

        $handler->instance_form_save((object)['id' => $badge->id, 'customfield_hours' => '40']);

        $html = display::render_values(badge_handler::create(), $badge->id, true);
        $this->assertStringContainsString('40', $html);
        $this->assertStringContainsString('area=badge', $html);

        $result = get_values::get_instances_values(badge_handler::create(), [$badge->id]);
        $this->assertSame('40', $result['instances'][0]['fields'][0]['value']);
    }

    /**
     * Deleting a badge deletes its values; archiving keeps them; deleting the course moves them to the system context.
     */
    public function test_deletion(): void {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $deleted = $this->create_badge();
        $archived = $this->create_badge();
        $coursebadge = $this->create_badge(['type' => BADGE_TYPE_COURSE, 'courseid' => $course->id]);
        $hours = $this->create_field('hours');
        /** @var \core_customfield_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_customfield');
        foreach ([$deleted, $archived, $coursebadge] as $badge) {
            $generator->add_instance_data($hours, $badge->id, '40');
        }

        $deleted->delete(false);
        $this->assertFalse($DB->record_exists('customfield_data', ['instanceid' => $deleted->id]));

        $archived->delete();
        $this->assertTrue($DB->record_exists('customfield_data', ['instanceid' => $archived->id]));

        delete_course($course, false);
        $this->assertEquals(\context_system::instance()->id, $DB->get_field('customfield_data', 'contextid', [
            'fieldid' => $hours->get('id'),
            'instanceid' => $coursebadge->id,
        ]));
    }

    /**
     * Backing up a course and restoring it into a new course copies the values to the restored badges.
     */
    public function test_backup_restore(): void {
        global $DB, $USER;
        $this->resetAfterTest();
        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $badge = $this->create_badge(['name' => 'Spreadsheet master', 'type' => BADGE_TYPE_COURSE, 'courseid' => $course->id]);
        $hours = $this->create_field('hours');
        /** @var \core_customfield_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_customfield');
        $generator->add_instance_data($hours, $badge->id, '40');

        $bc = new \backup_controller(
            \backup::TYPE_1COURSE,
            $course->id,
            \backup::FORMAT_MOODLE,
            \backup::INTERACTIVE_NO,
            \backup::MODE_GENERAL,
            $USER->id
        );
        $bc->execute_plan();
        $file = $bc->get_results()['backup_destination'];
        $bc->destroy();
        $file->extract_to_pathname(get_file_packer('application/vnd.moodle.backup'), make_backup_temp_directory('tbcf'));

        $newcourseid = \restore_dbops::create_new_course('Restored', 'RESTORED', $course->category);
        $rc = new \restore_controller(
            'tbcf',
            $newcourseid,
            \backup::INTERACTIVE_NO,
            \backup::MODE_GENERAL,
            $USER->id,
            \backup::TARGET_NEW_COURSE
        );
        $this->assertTrue($rc->execute_precheck());
        $rc->execute_plan();
        $rc->destroy();

        $newbadgeid = $DB->get_field('badge', 'id', ['courseid' => $newcourseid, 'name' => 'Spreadsheet master'], MUST_EXIST);
        $value = $DB->get_record('customfield_data', [
            'fieldid' => $hours->get('id'),
            'instanceid' => $newbadgeid,
        ], '*', MUST_EXIST);
        $this->assertSame('40', $value->value);
        $this->assertEquals(\context_course::instance($newcourseid)->id, $value->contextid);
        $this->assertSame('40', $DB->get_field('customfield_data', 'value', [
            'fieldid' => $hours->get('id'),
            'instanceid' => $badge->id,
        ]));
    }
}
