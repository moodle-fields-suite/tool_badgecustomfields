import moodle_docs_theme

project = "moodle-tool_badgecustomfields"
copyright = "2026 onwards, Kelson da Costa Medeiros"
author = "Kelson da Costa Medeiros"
release = "1.0.0"

extensions = [
    "sphinx.ext.githubpages",
    "moodle_docs_theme",
]

templates_path = []
exclude_patterns = ["_build", "Thumbs.db", ".DS_Store"]

language = "pt_BR"

html_theme = "moodle_docs_theme"
html_theme_path = [moodle_docs_theme.get_html_theme_path()]
html_logo = "../_static/logo.svg"
html_favicon = "../_static/favicon.ico"

html_theme_options = {
    "project_name": "moodle-tool_badgecustomfields",
    "tagline": "Campos personalizados para emblemas",
    "primary_color": "#0284c7",
    "secondary_color": "#6366f1",
    "github_url": "https://github.com/moodle-fields-suite/tool_badgecustomfields",
    "github_repo": "moodle-fields-suite/tool_badgecustomfields",
    "github_version": "main",
    "doc_path": "docs/pt_BR/",
    "show_edit_on_github": True,
    "enable_dark_mode": True,
    "enable_language_selector": True,
    "navigation_links": "Início|index, Instalação|installation, Configuração|configuration, Uso|usage",
}

html_static_path = []
