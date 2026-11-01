Instalação
==========

Requisitos
----------

* Moodle 4.5 LTS ou 5.3 LTS.
* PHP suportado pela sua versão do Moodle.
* `local_fieldssuite <https://moodle-fields-suite.github.io/local_fieldssuite/>`_, o plugin comum da Moodle Fields Suite (desde a 1.1.0). Instale-o antes, ou junto.

A partir de um arquivo ZIP
--------------------------

1. Baixe ``tool_badgecustomfields-<versão>.zip`` nas `releases <https://github.com/moodle-fields-suite/tool_badgecustomfields/releases>`_.
2. Acesse *Administração do site > Plugins > Instalar plugins*, envie o ZIP e siga os passos.
3. Conclua a atualização em *Administração do site > Notificações*.

A partir do código-fonte
------------------------

Copie o repositório para ``admin/tool/badgecustomfields`` (``public/admin/tool/badgecustomfields`` no Moodle 5.1 ou posterior) e acesse *Administração do site > Notificações*:

.. code-block:: bash

   git clone https://github.com/moodle-fields-suite/tool_badgecustomfields.git public/admin/tool/badgecustomfields

Idiomas
-------

O pacote traz apenas as strings em inglês. Os demais idiomas, inclusive o português do Brasil, vêm da ferramenta de tradução do Moodle (AMOS), pelos pacotes de idioma.
