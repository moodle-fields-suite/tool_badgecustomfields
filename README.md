# moodle-tool_badgecustomfields

[English](#english) · [Português (Brasil)](#português-brasil)

Documentation / Documentação: <https://moodle-fields-suite.github.io/tool_badgecustomfields/>

## English

Custom fields for badges, part of the [Moodle Fields Suite](https://moodle-fields-suite.github.io). Moodle has custom fields for courses, cohorts and groups, but not for badges: this admin tool adds a badge area, for site and course badges, with every field type installed on the site. Fields are managed at *Site administration > Badges*; values are edited from the badge pages, shown there according to each field's visibility, read by integrations through the `local_fieldssuite_get_values` web service, and kept in course backups and restores. Requires [`local_fieldssuite`](https://github.com/moodle-fields-suite/local_fieldssuite). Supports Moodle 4.5 LTS and 5.3 LTS.

Install: copy this repository to `admin/tool/badgecustomfields` (`public/admin/tool/badgecustomfields` on Moodle 5.1+), with `local_fieldssuite`, and visit *Site administration > Notifications*.

## Português (Brasil)

Campos personalizados para emblemas, parte da [Moodle Fields Suite](https://moodle-fields-suite.github.io). O Moodle tem campos personalizados para cursos, coortes e grupos, mas não para emblemas: esta ferramenta administrativa acrescenta uma área de emblemas, para emblemas do site e de curso, com todos os tipos de campo instalados no site. Os campos são geridos em *Administração do site > Emblemas*; os valores são editados a partir das páginas do emblema, aparecem nelas conforme a visibilidade de cada campo, podem ser lidos por integrações pelo web service `local_fieldssuite_get_values` e acompanham o backup e a restauração do curso. Requer o [`local_fieldssuite`](https://github.com/moodle-fields-suite/local_fieldssuite). Suporta Moodle 4.5 LTS e 5.3 LTS.

Instalação: copie este repositório para `admin/tool/badgecustomfields` (`public/admin/tool/badgecustomfields` no Moodle 5.1+), junto com o `local_fieldssuite`, e acesse *Administração do site > Notificações*.

## Development / Desenvolvimento

- CI (`.github/workflows/ci.yml`) runs `moodle-plugin-ci` on Moodle 4.5 and 5.3 with PostgreSQL and MariaDB, installing `local_fieldssuite`; run it locally with [`act`](https://github.com/nektos/act) before pushing.

## Support and privacy / Suporte e privacidade

- Issue tracker / Rastreador de issues: <https://github.com/moodle-fields-suite/tool_badgecustomfields/issues>
- Privacy / Privacidade: the values belong to badges, not to users; nothing leaves Moodle / os valores pertencem aos emblemas, não aos usuários; nada sai do Moodle.
- Translations / Traduções: only English is shipped; other languages go through AMOS / o pacote traz só inglês; outros idiomas via AMOS.

## License / Licença

GNU GPL v3 or later. See [LICENSE](LICENSE).
