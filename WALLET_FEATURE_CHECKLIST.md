# Wallet Feature Checklist

This checklist is based on the current Laravel codebase. It separates features that already exist from the items that still need implementation or hardening.

## Current Coverage

### Wallet Security

- Partial: Password confirmation exists for transfer and scan/pay (`passwordCheck`, API password checks).
- Partial: Login failed-attempt limit and temporary lock exists through `LoginSecurityService`.
- Partial: User account status exists (`Active`, `InActive`), but wallet-level disable is missing.
- Partial: Balance checks exist before reduce/transfer, but database-level protection is missing.

### Transaction Safety

- Partial: Transfers and admin balance changes use database transactions.
- Partial: Transaction history exists in `transactions` and user transaction pages/API.
- Missing: Wallet row locking (`lockForUpdate`) during transfer/reduce operations.
- Missing: Duplicate transfer prevention.
- Missing: Database unique indexes for transaction code/reference.
- Missing: API idempotency key support.
- Missing: Ledger-grade balance snapshots.

### Wallet Features

- Present: Admin add money.
- Present: Admin reduce money.
- Present: Wallet-to-wallet transfer.
- Present: Scan and Pay.
- Present: Transaction list and transaction detail.
- Missing: Transaction receipt view/PDF/download.
- Missing: User-initiated cash in/cash out request workflow.

### Admin Features

- Present: User wallet list.
- Present: Admin wallet balance add/reduce.
- Missing: Cash in/cash out approval queue.
- Missing: Suspicious transaction review.
- Missing: Wallet disable/enable.
- Missing: Refund/reverse transaction.
- Missing: Admin permission separation for wallet operations.

### Notification

- Present: Transfer sent/received notifications.
- Present: Reduced-balance notification.
- Present: Database notifications.
- Present: Broadcast event exists for real-time notifications.
- Missing: Admin approval notifications.
- Missing: Failed/reversed/refunded transaction notifications.

### Reports

- Partial: Dashboard counts and recent transactions exist.
- Missing: Daily transaction report.
- Missing: Monthly transaction report.
- Missing: User wallet statement.
- Missing: Cash in/out report.
- Missing: Excel/PDF export.

### Audit Log

- Partial: Users store login IP/user agent.
- Missing: Wallet audit log table.
- Missing: Who added/reduced balance.
- Missing: Who approved/rejected transaction.
- Missing: Old balance/new balance.
- Missing: IP address/device info per wallet action.

### API Security

- Present: Passport is configured and API routes use `auth:api`.
- Partial: API response helper exists, but responses are not fully standardized.
- Partial: Request validation exists, but transfer validation is too loose.
- Missing: Idempotency key validation/storage.
- Missing: Wallet API rate limits.
- Missing: Consistent error codes/messages.

### Testing

- Missing: Transfer success test.
- Missing: Insufficient balance test.
- Missing: Duplicate request/idempotency test.
- Missing: Inactive user test.
- Missing: Locked wallet test.
- Missing: Concurrent transfer/race-condition test.

## High Priority Improvements

1. Move all wallet money movement into a single `WalletTransactionService`.
2. Lock sender and receiver wallet rows with `lockForUpdate()` inside the same DB transaction.
3. Add unique database constraints for `transactions.ref_no` and `transactions.trx_id`.
4. Add an idempotency table for API requests with `user_id`, `idempotency_key`, `request_hash`, `response_body`, and status.
5. Add wallet status fields: `active`, `disabled_at`, `disabled_by`, and `disabled_reason`.
6. Add wallet audit logs with actor, action, old balance, new balance, IP, user agent, and metadata.
7. Replace repeated transfer logic in web/API/scan-pay with one shared service method.
8. Add strict validation for transfer amount: numeric, min amount, max amount, decimal precision, and receiver exists.
9. Prevent negative balance at the database level with a check constraint where supported.
10. Add tests for normal, failed, duplicate, locked, inactive, and concurrent transfers.

## Suggested Extra Features

- Daily transfer limit and per-transaction max limit.
- New device login warning.
- PIN separate from login password.
- Two-factor confirmation for high-value transfers.
- Beneficiary/favorite receiver list.
- Transaction categories and notes.
- Pending/rejected/completed transaction statuses.
- Reversal transaction links to the original transaction.
- Fee support with configurable fee rules.
- Admin role permissions for viewer, operator, approver, and super admin.
- Suspicious transaction rules: high amount, high frequency, new receiver, repeated failed PIN/password, and rapid cash out after cash in.
- Exportable wallet statement with opening balance, credits, debits, and closing balance.
- Webhook/event log for external integrations.
- Queue notifications so money movement is not blocked by notification delivery.

## Recommended Implementation Order

1. Hardening first: row locks, unique indexes, stricter validation, and shared transfer service.
2. Safety workflows: idempotency, duplicate prevention, wallet disable, and audit logs.
3. Admin operations: approval queues, refund/reversal, and suspicious review.
4. Reporting: statements, daily/monthly reports, cash in/out report, and exports.
5. Tests: add coverage for every wallet state and failure path before expanding features further.
