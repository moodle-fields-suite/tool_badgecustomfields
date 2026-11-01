Backup and restore
==================

The values of course badges go with the course backup and come back when it is restored.

* The backup must include badges (the *Include badges* setting, available when badges are enabled). Archived badges are not backed up by Moodle, so their values are not either.
* On restore, each value is saved for the restored badge, in the context of the restored course. Restoring into a new course, into an existing course or importing works the same way: Moodle always restores course badges as new badges.
* Values are matched to the fields of the target site by short name and type. Values of fields that do not exist on the target site are skipped.
* The backup includes the values the user doing it can see or edit, as for course custom fields.
* Files embedded in text area values are not restored.

Site badges are not part of course backups.
