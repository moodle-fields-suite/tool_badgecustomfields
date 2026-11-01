# Changelog

## 1.0.0 - 2026-11-02

First release, built on `local_fieldssuite` 1.1.0.

### Added
- Badge custom field area, for site badges (values in the system context) and course badges (values in the course context), with every field type installed on the site.
- Field management at *Site administration > Badges > Badge custom fields*, with the area settings *Locked* and *Visible to*.
- Values shown, with an *Edit custom fields* link, on the badge overview page and on the badge details form; edited on the common page of `local_fieldssuite`.
- Course backup and restore of the values of the course badges.
- Values deleted when their badge is deleted, kept when it is archived, and moved to the system context when the course of a badge is deleted.
- Capabilities `tool/badgecustomfields:configurecustomfields`, `tool/badgecustomfields:edit` and `tool/badgecustomfields:changelockedcustomfields`.
- Privacy API provider, PHPUnit tests (including a real course backup and restore), CI with moodle-plugin-ci (Moodle 4.5 and 5.3, PostgreSQL and MariaDB), documentation in English and Brazilian Portuguese, release by draft and verification.
