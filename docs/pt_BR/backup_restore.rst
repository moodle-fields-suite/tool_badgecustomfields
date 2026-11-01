Backup e restauração
====================

Os valores dos emblemas de curso vão com o backup do curso e voltam quando ele é restaurado.

* O backup precisa incluir os emblemas (opção *Incluir emblemas*, disponível quando os emblemas estão habilitados). Emblemas arquivados não entram no backup do Moodle, e os valores deles também não.
* Na restauração, cada valor é gravado no emblema restaurado, no contexto do curso restaurado. Restaurar em um curso novo, em um curso existente ou importar funciona igual: o Moodle sempre restaura os emblemas de curso como emblemas novos.
* Os valores são associados aos campos do site de destino pelo nome breve e pelo tipo. Valores de campos que não existem no site de destino são ignorados.
* O backup inclui os valores que quem o faz pode ver ou editar, como nos campos personalizados do curso.
* Arquivos incorporados em valores de área de texto não são restaurados.

Emblemas do site não fazem parte do backup do curso.
