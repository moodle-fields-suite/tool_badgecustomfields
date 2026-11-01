Installation
============

Requirements
------------

* Moodle 4.5 LTS or 5.3 LTS.
* PHP supported by your Moodle version.
* `local_fieldssuite <https://moodle-fields-suite.github.io/local_fieldssuite/>`_, the common plugin of the Moodle Fields Suite (since 1.1.0). Install it first, or together.

From a ZIP file
---------------

1. Download ``tool_badgecustomfields-<version>.zip`` from the `releases <https://github.com/moodle-fields-suite/tool_badgecustomfields/releases>`_.
2. Go to *Site administration > Plugins > Install plugins*, upload the ZIP and follow the steps.
3. Finish the upgrade at *Site administration > Notifications*.

From source
-----------

Copy the repository to ``admin/tool/badgecustomfields`` (``public/admin/tool/badgecustomfields`` on Moodle 5.1 or later) and visit *Site administration > Notifications*:

.. code-block:: bash

   git clone https://github.com/moodle-fields-suite/tool_badgecustomfields.git public/admin/tool/badgecustomfields

Languages
---------

The package ships only the English strings. Other languages, including Brazilian Portuguese, come from the Moodle translation tool (AMOS), through the language packs.
