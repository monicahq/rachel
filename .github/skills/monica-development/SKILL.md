---
name: monica-development
description: Use this skill when developing features in the Monica application (the "rachel" workspace folder). Activate for any task that involves building a Livewire page, creating a service in app/Services/, adding an API endpoint with Scribe documentation, or writing the corresponding Pest tests for these layers in Rachel. Trigger on mentions of "Monica", "Rachel", "feature", "page", "service", "endpoint", "API", "Scribe", or when the user asks to add, edit, or test a vault/contact-style resource. Do NOT use for the other Monica workspace folders (monica, chandler, geller, rabbit, livewire), nor for pure styling, authentication setup (use fortify-development), or generic Laravel/Pest scaffolding already covered by the laravel-development and pest-testing skills.
---

# Monica Development (Rachel)

This skill describes how to develop a complete feature in the Rachel application: a Livewire page, a service, an API endpoint with Scribe documentation, and the matching Pest tests.

## When to Use

Use this skill when adding or editing a feature that touches one or more of these layers in Rachel:

1. A Livewire single-file page under `resources/views/pages/`.
2. A service class under `app/Services/`.
3. An API controller under `app/Http/Controllers/Api/` with Scribe attributes.
4. Pest tests under `tests/Feature/` or `tests/Unit/`.

Most non-trivial features touch all four layers. Implement them in the order: **service → API → page → tests**, or **service → page → API → tests** depending on the user-facing priority.

## Workflow

For each layer, read the matching reference file. Each reference contains the conventions, exact commands, code patterns, and the canonical example file in the repo to mirror.

| Step | Layer | Reference |
| ---- | ----- | --------- |
| 1 | Livewire page (SFC) + route registration | [references/pages.md](references/pages.md) |
| 2 | Service in `app/Services/` | [references/services.md](references/services.md) |
| 3 | API controller + Scribe attributes + route | [references/api.md](references/api.md) |
| 4 | Pest tests (Feature page, Unit service, Unit API) | [references/tests.md](references/tests.md) |

## Cross-Cutting Conventions

These apply to every PHP file you touch:

- `declare(strict_types=1);` at the top of every PHP file.
- Classes are `final` unless they are abstract base classes.
- PHP 8.4 constructor property promotion with `readonly` for value objects (services, DTOs).
- Type-hint every parameter and return type explicitly.
- User-facing strings go through `__('...')` (translations live in `lang/en.json` and `lang/fr.json`).
- Authorization is enforced in the page (`$this->authorize(...)`) **and** in the API controller (`$this->authorizeResource(...)`).
- Validation uses `Model::rules()` static methods (e.g. `Vault::rules()`).
- After editing PHP files, run `vendor/bin/pint --dirty --format agent`.
- After editing tests or related code, run `php artisan test --compact --filter=YourTestName`.

## Related Skills

- For Livewire syntax details (wire: directives, islands, reactivity), see the `livewire-development` skill.
- For Flux UI components and Tailwind classes in Blade, see `fluxui-development` and `tailwindcss-development`.
- For Pest syntax and browser tests, see `pest-testing`.
- For authentication (login, 2FA, passkeys, profile), see `fortify-development`.

This skill focuses on **how Rachel composes these layers together**, not on each technology in isolation.
