# Panchi Gallery API Documentation

## Base URL
```
https://panchigallery.com/api
```

## Authentication
All admin API endpoints require authentication using Laravel Sanctum.

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success message",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

### Paginated Response
```json
{
  "success": true,
  "message": "Success message",
  "data": [ ... ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7,
    "from": 1,
    "to": 15
  }
}
```

## Standard Query Parameters

### Pagination
- `per_page`: Number of items per page (default: 15, max: 100)
- `page`: Page number (default: 1)

### Sorting
- `sort_by`: Field to sort by
- `sort_order`: Sort direction (asc, desc, default: desc)

### Filtering
- `search`: Search in text fields
- `date_from`: Filter by date (from)
- `date_to`: Filter by date (to)
- `{field}`: Filter by specific field

## Public Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `GET /api/auth/me` - Get current user (authenticated)
- `POST /api/auth/logout` - Logout user (authenticated)

### Public Artworks
- `GET /api/public/artworks` - List artworks
- `GET /api/public/artworks/{slug}` - Get artwork details
- `GET /api/public/artworks/{slug}/similar` - Get similar artworks
- `GET /api/public/categories` - List categories

### Public Artists
- `GET /api/public/artists` - List artists
- `GET /api/public/artists/{slug}` - Get artist details

## Admin Endpoints

### Analytics
- `GET /api/admin/dashboard` - Dashboard analytics
- `GET /api/admin/health` - System health status

### User Management
- `GET /api/admin/users` - List users
- `POST /api/admin/users` - Create user
- `GET /api/admin/users/{id}` - Get user details
- `PUT /api/admin/users/{id}` - Update user
- `DELETE /api/admin/users/{id}` - Delete user
- `POST /api/admin/users/{id}/approve` - Approve user
- `POST /api/admin/users/{id}/reject` - Reject user
- `POST /api/admin/users/bulk-action` - Bulk user actions

### Artist Management
- `GET /api/admin/artists` - List artists
- `POST /api/admin/artists` - Create artist
- `GET /api/admin/artists/{id}` - Get artist details
- `PUT /api/admin/artists/{id}` - Update artist
- `DELETE /api/admin/artists/{id}` - Delete artist
- `POST /api/admin/artists/{id}/approve` - Approve artist
- `POST /api/admin/artists/{id}/reject` - Reject artist

### Artwork Moderation
- `GET /api/admin/artworks` - List artworks
- `POST /api/admin/artworks` - Create artwork
- `GET /api/admin/artworks/{id}` - Get artwork details
- `PUT /api/admin/artworks/{id}` - Update artwork
- `DELETE /api/admin/artworks/{id}` - Delete artwork
- `POST /api/admin/artworks/{id}/approve` - Approve artwork
- `POST /api/admin/artworks/{id}/reject` - Reject artwork
- `GET /api/admin/resales` - List resales
- `POST /api/admin/resales/{id}/approve` - Approve resale

### Exhibition Management
- `GET /api/admin/exhibitions` - List exhibitions
- `POST /api/admin/exhibitions` - Create exhibition
- `GET /api/admin/exhibitions/{id}` - Get exhibition details
- `PUT /api/admin/exhibitions/{id}` - Update exhibition
- `DELETE /api/admin/exhibitions/{id}` - Delete exhibition
- `POST /api/admin/exhibitions/{id}/restore` - Restore exhibition
- `POST /api/admin/exhibitions/{id}/artists` - Add artists
- `DELETE /api/admin/exhibitions/{id}/artists` - Remove artists
- `POST /api/admin/exhibitions/{id}/artworks` - Add artworks
- `DELETE /api/admin/exhibitions/{id}/artworks` - Remove artworks
- `PUT /api/admin/exhibitions/{id}/status` - Update status
- `POST /api/admin/exhibitions/{id}/toggle-featured` - Toggle featured
- `POST /api/admin/exhibitions/{id}/toggle-published` - Toggle published

### Blog Management
- `GET /api/admin/blogs` - List blogs
- `POST /api/admin/blogs` - Create blog
- `GET /api/admin/blogs/{id}` - Get blog details
- `PUT /api/admin/blogs/{id}` - Update blog
- `DELETE /api/admin/blogs/{id}` - Delete blog
- `POST /api/admin/blogs/{id}/restore` - Restore blog
- `PUT /api/admin/blogs/{id}/status` - Update status
- `POST /api/admin/blogs/{id}/toggle-featured` - Toggle featured

### Collection Management
- `GET /api/admin/collections` - List collections
- `POST /api/admin/collections` - Create collection
- `GET /api/admin/collections/{id}` - Get collection details
- `PUT /api/admin/collections/{id}` - Update collection
- `DELETE /api/admin/collections/{id}` - Delete collection
- `POST /api/admin/collections/{id}/artworks` - Add artworks
- `DELETE /api/admin/collections/{id}/artworks` - Remove artworks
- `PUT /api/admin/collections/{id}/order` - Update order
- `POST /api/admin/collections/{id}/toggle-featured` - Toggle featured
- `POST /api/admin/collections/{id}/toggle-active` - Toggle active

### Commission Management
- `GET /api/admin/commissions` - List commissions
- `GET /api/admin/commissions/{id}` - Get commission details
- `PUT /api/admin/commissions/{id}` - Update commission
- `POST /api/admin/commissions/{id}/mark-paid` - Mark as paid
- `GET /api/admin/commissions/statistics` - Statistics
- `POST /api/admin/commissions/bulk-mark-paid` - Bulk mark as paid

### Payout Management
- `GET /api/admin/payouts` - List payouts
- `GET /api/admin/payouts/{id}` - Get payout details
- `PUT /api/admin/payouts/{id}` - Update payout
- `POST /api/admin/payouts/{id}/process` - Process payout
- `POST /api/admin/payouts/{id}/complete` - Complete payout
- `POST /api/admin/payouts/{id}/reject` - Reject payout
- `GET /api/admin/payouts/statistics` - Statistics
- `POST /api/admin/payouts/bulk-process` - Bulk process

### Shipment Management
- `GET /api/admin/shipments` - List shipments
- `GET /api/admin/shipments/{id}` - Get shipment details
- `PUT /api/admin/shipments/{id}` - Update shipment
- `PUT /api/admin/shipments/{id}/status` - Update status
- `POST /api/admin/shipments/{id}/tracking-event` - Add tracking event
- `GET /api/admin/shipments/{id}/track` - Track shipment
- `GET /api/admin/shipments/statistics` - Statistics

### Payment Proof Management
- `GET /api/admin/payment-proofs` - List payment proofs
- `GET /api/admin/payment-proofs/{id}` - Get payment proof details
- `PUT /api/admin/payment-proofs/{id}` - Update payment proof
- `POST /api/admin/payment-proofs/{id}/approve` - Approve payment proof
- `POST /api/admin/payment-proofs/{id}/reject` - Reject payment proof
- `GET /api/admin/payment-proofs/statistics` - Statistics
- `POST /api/admin/payment-proofs/bulk-approve` - Bulk approve
- `POST /api/admin/payment-proofs/bulk-reject` - Bulk reject

### Promo Code Management
- `GET /api/admin/promo-codes` - List promo codes
- `POST /api/admin/promo-codes` - Create promo code
- `GET /api/admin/promo-codes/{id}` - Get promo code details
- `PUT /api/admin/promo-codes/{id}` - Update promo code
- `DELETE /api/admin/promo-codes/{id}` - Delete promo code
- `POST /api/admin/promo-codes/{id}/toggle-active` - Toggle active
- `POST /api/admin/promo-codes/{id}/reset-usage` - Reset usage
- `GET /api/admin/promo-codes/statistics` - Statistics

### Contact Message Management
- `GET /api/admin/contact-messages` - List contact messages
- `GET /api/admin/contact-messages/{id}` - Get message details
- `PUT /api/admin/contact-messages/{id}` - Update message
- `DELETE /api/admin/contact-messages/{id}` - Delete message
- `POST /api/admin/contact-messages/{id}/mark-read` - Mark as read
- `POST /api/admin/contact-messages/{id}/mark-unread` - Mark as unread
- `PUT /api/admin/contact-messages/{id}/status` - Update status
- `POST /api/admin/contact-messages/{id}/respond` - Respond to message
- `POST /api/admin/contact-messages/bulk-mark-read` - Bulk mark as read
- `POST /api/admin/contact-messages/bulk-delete` - Bulk delete
- `GET /api/admin/contact-messages/statistics` - Statistics

### FAQ Management
- `GET /api/admin/faqs` - List FAQs
- `POST /api/admin/faqs` - Create FAQ
- `GET /api/admin/faqs/{id}` - Get FAQ details
- `PUT /api/admin/faqs/{id}` - Update FAQ
- `DELETE /api/admin/faqs/{id}` - Delete FAQ
- `POST /api/admin/faqs/{id}/toggle-published` - Toggle published
- `PUT /api/admin/faqs/order` - Update order
- `GET /api/admin/faqs/categories` - Get categories
- `GET /api/admin/faqs/statistics` - Statistics

### Page Content Management
- `GET /api/admin/page-contents` - List page contents
- `POST /api/admin/page-contents` - Create page content
- `GET /api/admin/page-contents/{id}` - Get page content details
- `PUT /api/admin/page-contents/{id}` - Update page content
- `DELETE /api/admin/page-contents/{id}` - Delete page content
- `POST /api/admin/page-contents/{id}/toggle-active` - Toggle active
- `POST /api/admin/page-contents/bulk-update` - Bulk update
- `GET /api/admin/page-contents/by-page-section` - Get by page/section
- `GET /api/admin/page-contents/pages` - Get pages
- `GET /api/admin/page-contents/sections` - Get sections

### Exchange Rate Management
- `GET /api/admin/exchange-rates` - List exchange rates
- `POST /api/admin/exchange-rates` - Create exchange rate
- `GET /api/admin/exchange-rates/{id}` - Get exchange rate details
- `PUT /api/admin/exchange-rates/{id}` - Update exchange rate
- `DELETE /api/admin/exchange-rates/{id}` - Delete exchange rate
- `GET /api/admin/exchange-rates/current` - Get current rate
- `GET /api/admin/exchange-rates/history` - Get history
- `POST /api/admin/exchange-rates/auto-update` - Auto update
- `GET /api/admin/exchange-rates/statistics` - Statistics

### Role Management
- `GET /api/admin/roles` - List roles
- `POST /api/admin/roles` - Create role
- `GET /api/admin/roles/{id}` - Get role details
- `PUT /api/admin/roles/{id}` - Update role
- `DELETE /api/admin/roles/{id}` - Delete role
- `POST /api/admin/roles/{id}/permissions` - Assign permissions
- `GET /api/admin/roles/available-permissions` - Get available permissions
- `POST /api/admin/roles/{id}/toggle-active` - Toggle active

### Permission Management
- `GET /api/admin/permissions` - List permissions
- `POST /api/admin/permissions` - Create permission
- `GET /api/admin/permissions/{id}` - Get permission details
- `PUT /api/admin/permissions/{id}` - Update permission
- `DELETE /api/admin/permissions/{id}` - Delete permission
- `GET /api/admin/permissions/modules` - Get modules
- `POST /api/admin/permissions/{id}/toggle-active` - Toggle active

### Category Management
- `GET /api/admin/categories` - List categories
- `POST /api/admin/categories` - Create category
- `GET /api/admin/categories/{id}` - Get category details
- `PUT /api/admin/categories/{id}` - Update category
- `DELETE /api/admin/categories/{id}` - Delete category
- `POST /api/admin/categories/{id}/toggle-active` - Toggle active
- `PUT /api/admin/categories/order` - Update order

### Order Management
- `GET /api/admin/orders` - List orders
- `GET /api/admin/orders/{id}` - Get order details
- `PUT /api/admin/orders/{id}` - Update order
- `POST /api/admin/orders/{id}/cancel` - Cancel order
- `POST /api/admin/orders/{id}/refund` - Refund order
- `POST /api/admin/orders/{id}/hold` - Put on hold
- `POST /api/admin/orders/{id}/release` - Release from hold
- `GET /api/admin/orders/statistics` - Statistics

### Transaction Management
- `GET /api/admin/transactions` - List transactions
- `GET /api/admin/transactions/{id}` - Get transaction details
- `PUT /api/admin/transactions/{id}` - Update transaction
- `POST /api/admin/transactions/{id}/complete` - Complete transaction
- `GET /api/admin/transactions/statistics` - Statistics

### Settings
- `GET /api/admin/settings` - Get settings
- `PUT /api/admin/settings` - Update settings

## Error Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting
API endpoints are rate limited to prevent abuse. Default limit: 100 requests per minute per IP.

## Version
Current API Version: 1.0.0
