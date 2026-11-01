moodle-tool_badgecustomfields
=============================

**Custom fields for badges: manage them like course custom fields, edit the values from the badge pages, keep them in course backups and read them in integrations.**

.. figure:: img/display.png
   :alt: The badge overview page with the custom field values and the Edit custom fields button below the badge name.
   :width: 100%

   The values of a badge, with the link to edit them.

* **Fields where Moodle has none.** Moodle has custom fields for courses, cohorts and groups, but not for badges. This tool adds a badge area, for site and course badges.
* **Every field type.** Text, text area, menu, checkbox, date and any other custom field type installed on the site, with the usual settings.
* **Control who sees each value.** Every field is visible to everyone, only to those who can edit the values, or to nobody.
* **Kept in backups.** The values of course badges go with the course backup and come back on restore.
* **Ready for integrations.** The ``local_fieldssuite_get_values`` web service returns the values the user can see.

Part of the `Moodle Fields Suite <https://moodle-fields-suite.github.io>`_; requires `local_fieldssuite <https://moodle-fields-suite.github.io/local_fieldssuite/>`_.

Versão em português: `Português (Brasil) <../pt-br/index.html>`_.

.. toctree::
   :maxdepth: 2
   :caption: Contents

   installation
   configuration
   usage
   backup_restore

Links
-----

* Source code and issues: https://github.com/moodle-fields-suite/tool_badgecustomfields
