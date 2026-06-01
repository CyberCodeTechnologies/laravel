<!-- Use this PR template when submitting security fixes or dependency updates -->

## Summary
- Brief description of the security change and reasoning.

## Changes
- Updated files/dependencies:
  - 

## Verification checklist
- [ ] Run `composer install --no-dev` and verify no unexpected packages installed
- [ ] Run `composer audit` and confirm advisories fixed
- [ ] Run `vendor/bin/phpstan analyse` (if available) and fix any critical issues
- [ ] Confirm no secrets (.env) were committed and any `.env` changes were applied only via safe mechanism
- [ ] Confirm backups and file permissions are correct for `storage/` and `bootstrap/cache`

## Rollback plan
- Steps to revert change if needed (e.g. revert PR, restore DB from backup)

## Notes
- If the change modifies any runtime configuration, ensure the ops team performs a controlled deploy.
