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

namespace tool_badgecustomfields\customfield;

use core_badges\badge;
use local_fieldssuite\customfield\area_handler;

/**
 * Custom field handler for badges.
 *
 * The instance id is the badge id; the values live in the context of the badge (system for site badges,
 * course for course badges).
 *
 * @package    tool_badgecustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class badge_handler extends area_handler {
    /**
     * Capabilities of the area.
     *
     * @param string $type One of the CAPABILITY_* constants.
     * @return string
     */
    public function get_capability(string $type): string {
        return [
            self::CAPABILITY_CONFIGURE => 'tool/badgecustomfields:configurecustomfields',
            self::CAPABILITY_EDIT => 'tool/badgecustomfields:edit',
            self::CAPABILITY_CHANGELOCKED => 'tool/badgecustomfields:changelockedcustomfields',
        ][$type];
    }

    /**
     * Admin page that manages the fields.
     *
     * @return string
     */
    public function get_admin_section(): string {
        return 'tool_badgecustomfields_badge';
    }

    /**
     * A badge, or null when it does not exist.
     *
     * @param int $instanceid Badge id.
     * @return badge|null
     */
    protected function get_badge(int $instanceid): ?badge {
        global $CFG, $DB;
        require_once($CFG->libdir . '/badgeslib.php');
        if ($instanceid <= 0 || !$DB->record_exists('badge', ['id' => $instanceid])) {
            return null;
        }
        return new badge($instanceid);
    }

    /**
     * Context of a badge.
     *
     * @param int $instanceid Badge id.
     * @return \context
     */
    public function get_instance_context(int $instanceid = 0): \context {
        $badge = $this->get_badge($instanceid);
        return $badge ? $badge->get_context() : \context_system::instance();
    }

    /**
     * A badge the current user can see.
     *
     * Users who can manage the badges of the context see every badge; other users see the badges that are
     * available to earn, like on the badge page.
     *
     * @param int $instanceid Badge id.
     * @return \stdClass|null
     */
    public function get_instance_info(int $instanceid): ?\stdClass {
        global $CFG;
        $badge = $this->get_badge($instanceid);
        if (!$badge || empty($CFG->enablebadges) || $badge->status == BADGE_STATUS_ARCHIVED) {
            return null;
        }
        if ($badge->type == BADGE_TYPE_COURSE && empty($CFG->badges_allowcoursebadges)) {
            return null;
        }
        $context = $badge->get_context();
        // Same capabilities as badges_can_manage_badges() of Moodle 5.3, which Moodle 4.5 does not have.
        $canmanage = has_any_capability([
            'moodle/badges:viewawarded',
            'moodle/badges:createbadge',
            'moodle/badges:awardbadge',
            'moodle/badges:configurecriteria',
            'moodle/badges:configuremessages',
            'moodle/badges:configuredetails',
            'moodle/badges:deletebadge',
        ], $context);
        if (!$canmanage && !$badge->is_active()) {
            return null;
        }
        return (object)[
            'name' => format_string($badge->name, true, ['context' => $context]),
            'url' => new \moodle_url('/badges/overview.php', ['id' => $instanceid]),
            'context' => $context,
        ];
    }

    /**
     * The edit page belongs to the badge: same layout and navigation as the badge overview page.
     *
     * @param int $instanceid Badge id.
     * @param \moodle_url $url
     */
    public function set_up_edit_page(int $instanceid, \moodle_url $url): void {
        global $PAGE;
        $badge = $this->get_badge($instanceid);
        $context = $badge->get_context();
        if ($badge->type == BADGE_TYPE_COURSE) {
            require_login($badge->courseid);
            $PAGE->set_pagelayout('standard');
            \navigation_node::override_active_url(new \moodle_url('/badges/index.php', [
                'type' => $badge->type,
                'id' => $badge->courseid,
            ]));
        } else {
            $PAGE->set_pagelayout('admin');
            \navigation_node::override_active_url(new \moodle_url('/badges/index.php', ['type' => $badge->type]), true);
        }
        $PAGE->set_context($context);
        $PAGE->set_url($url);
        $PAGE->navbar->add(
            format_string($badge->name, true, ['context' => $context]),
            new \moodle_url('/badges/overview.php', ['id' => $instanceid])
        );
    }

    /**
     * Saves a value restored from a course backup for a restored badge.
     *
     * The field is found by short name and type; values of fields that do not exist on this site are skipped.
     *
     * @param int $badgeid Id of the restored badge.
     * @param array $data Value from the backup: shortname, type, value, valueformat, valuetrust.
     * @return int|null Id of the saved value, or null when the field does not exist.
     */
    public function restore_value(int $badgeid, array $data): ?int {
        $context = $this->get_instance_context($badgeid);
        foreach ($this->get_instance_data($badgeid, true) as $d) {
            $field = $d->get_field();
            if ($field->get('shortname') === $data['shortname'] && $field->get('type') === $data['type']) {
                $d->set($d->datafield(), $data['value']);
                $d->set('value', $data['value']);
                $d->set('valueformat', $data['valueformat']);
                $d->set('valuetrust', !empty($data['valuetrust']));
                $d->set('contextid', $context->id);
                $d->save();
                return (int)$d->get('id');
            }
        }
        return null;
    }
}
