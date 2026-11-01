moodle-tool_badgecustomfields
=============================

**Campos personalizados para emblemas: gerencie como os campos personalizados do curso, edite os valores a partir das páginas do emblema, mantenha-os no backup do curso e leia-os em integrações.**

.. figure:: img/display.png
   :alt: Página de visão geral de um emblema com os valores dos campos personalizados e o botão Edit custom fields abaixo do nome do emblema.
   :width: 100%

   Os valores de um emblema, com o link para editá-los.

* **Campos onde o Moodle não tem.** O Moodle tem campos personalizados para cursos, coortes e grupos, mas não para emblemas. Esta ferramenta acrescenta uma área de emblemas, para emblemas do site e de curso.
* **Todos os tipos de campo.** Texto, área de texto, menu, caixa de seleção, data e qualquer outro tipo instalado no site, com as configurações de sempre.
* **Controle de quem vê cada valor.** Cada campo é visível para todos, só para quem pode editar os valores ou para ninguém.
* **Mantidos no backup.** Os valores dos emblemas de curso vão com o backup do curso e voltam na restauração.
* **Pronto para integrações.** O web service ``local_fieldssuite_get_values`` devolve os valores que o usuário pode ver.

Faz parte da `Moodle Fields Suite <https://moodle-fields-suite.github.io>`_; requer o `local_fieldssuite <https://moodle-fields-suite.github.io/local_fieldssuite/>`_.

English version: `English <../en/index.html>`_.

.. toctree::
   :maxdepth: 2
   :caption: Conteúdo

   installation
   configuration
   usage
   backup_restore

Links
-----

* Código-fonte e issues: https://github.com/moodle-fields-suite/tool_badgecustomfields
