# PanchiGallery.com - System Overview

## Project Information

**Project Name**: PanchiGallery  
**Framework**: Laravel 11.53.1  
**PHP Version**: 8.2.12  
**Database**: MySQL  
**Server**: http://127.0.0.1:8000  
**Total Routes**: 537  
**Total Migrations**: 68  

---

## System Architecture

PanchiGallery is a comprehensive art marketplace platform built with Laravel, featuring role-based access control for artists, collectors, and administrators. The system supports artwork management, certificate generation with QR codes, custom orders, and payment processing for both Myanmar and international markets.

---

## User Roles & Permissions

### 1. Admin
- Full system access
- User management (approve/reject artists)
- Artwork moderation (approve/reject artworks)
- Payment verification
- Order management
- Exhibition and blog management
- Commission and payout management

### 2. Artist
- Create and manage artworks
- Track sales and earnings
- Manage profile (bio, exhibitions, awards, social links)
- Accept/reject custom orders
- View followers and analytics
- Generate certificates for artworks

### 3. Collector
- Browse and purchase artworks
- Manage collection and wishlist
- Create resale listings
- Follow artists
- Transfer artwork ownership
- Place custom orders

---

## Core Features

### Authentication System
- **Registration**: Role selection (artist/collector) with auto-approval for collectors
- **Login**: Role-based redirects to appropriate dashboards
- **Artist Approval**: Pending approval workflow for new artists
- **Email Verification**: Optional email verification
- **Password Reset**: Full password reset functionality
- **Rate Limiting**: Login attempts throttled (5 per minute)

### Artwork Management
- **CRUD Operations**: Create, edit, delete artworks
- **Image Uploads**: Up to 10 images, 5MB each (JPEG, PNG, GIF, WebP)
- **Status Workflow**: draft → pending → approved → sold
- **Stock Management**: Real-time stock tracking and validation
- **Currency Support**: USD, MMK with automatic conversion
- **Categories**: Comprehensive category system
- **Filters**: Filter by category, medium, artist, size, price
- **QR Codes**: Generated for each artwork page using external API (api.qrserver.com)
- **View Tracking**: View count analytics
- **Mediums**: oil, acrylic, watercolor, digital, photography, sculpture, mixed_media, other, traditional

### Certificate System
- **QR Code Generation**: Using `simplesoftwareio/simple-qrcode` package
- **PDF Generation**: Using `barryvdh/laravel-dompdf` package
- **Verification**: Via certificate code and API endpoint
- **Digital Signatures**: Artist and platform digital signatures
- **Ownership History**: Complete tracking of artwork ownership
- **Download**: PDF certificate download for owners

### Custom Orders
- **Order Submission**: Comprehensive form with shape/size options
- **Shapes**: Rectangle, Square, Circle, Oval, Triangle, Custom
- **Size Calculation**: Automatic pricing based on dimensions
- **Mediums**: Oil painting, Acrylic, Watercolor, Digital art, Pencil drawing, Charcoal, Mixed media, Sculpture, Photography
- **Styles**: Realism, Abstract, Impressionism, Modern, Contemporary, Traditional, Minimalist, Pop Art, Surrealism
- **Reference Images**: Multiple image uploads
- **Artist Workflow**: Accept/reject, start progress, mark ready for review
- **Customer Workflow**: Approve/reject completed artwork
- **Status Tracking**: 
  - pending_artist_approval
  - artist_accepted
  - artist_rejected
  - in_progress
  - ready_for_review
  - customer_approved
  - customer_rejected
  - shipped
  - delivered
  - cancelled

### Cart & Checkout
- **Session-based Cart**: Persists for guests and authenticated users
- **Stock Validation**: Real-time stock checking
- **Promo Codes**: Discount code system with validation
- **Shipping Methods**: Standard, Express, Premium
- **Shipping Calculation**: Automatic shipping cost calculation
- **Guest Checkout**: Full guest checkout support
- **Gift Options**: Gift messaging and gift receipts
- **Order Notes**: Customer notes support

### Payment Processing
- **Myanmar Mobile Payments**:
  - KBZ Pay
  - Wave Pay
  - AYA Pay
  - UAB Pay
  - MMQR
- **Bank Transfer**: With manual verification
- **Payment Proof Upload**: Screenshot upload for manual payments
- **International Payments**: Stripe, PayPal support
- **Admin Verification**: Payment proof approval/rejection workflow
- **Status Tracking**: pending, completed, failed

### Resale Marketplace
- **Create Listings**: Collectors can list owned artworks for resale
- **Manage Listings**: Edit price, description, images
- **Purchase**: Other collectors can purchase resale artworks
- **Ownership Transfer**: Automatic ownership transfer on purchase

### Wishlist
- **Add to Wishlist**: Save favorite artworks
- **Remove from Wishlist**: Manage wishlist items
- **Toggle**: Quick add/remove functionality

### Following System
- **Follow Artists**: Collectors can follow artists
- **View Followers**: Artists can view their followers
- **Followed Artists**: Collectors can view followed artists

### Analytics
- **Artist Analytics**:
  - Monthly sales
  - Views by category
  - Top artworks
  - Earnings tracking
- **Collector Analytics**:
  - Collection value
  - Purchase history
  - Wishlist statistics

---

## Database Schema

### Core Tables
- **users**: User accounts with role-based fields
- **roles**: Role definitions for granular permissions
- **permissions**: Permission definitions
- **role_user**: Pivot table for role-user relationships
- **permission_role**: Pivot table for permission-role relationships
- **artists**: Artist profile extensions
- **artworks**: Artwork listings
- **categories**: Artwork categories
- **certificates**: Certificate records with QR codes
- **ownerships**: Artwork ownership records
- **transactions**: Transaction records
- **resales**: Resale listings
- **orders**: Order records (regular and custom)
- **order_items**: Order line items
- **carts**: Shopping carts
- **cart_items**: Cart line items
- **wishlists**: Wishlist records
- **followers**: Artist follower relationships
- **likes**: Artwork likes
- **payment_methods**: Payment method configurations
- **payment_proofs**: Payment proof uploads
- **commissions**: Artist commission records
- **payouts**: Artist payout records
- **shipments**: Shipment tracking
- **exhibitions**: Exhibition records
- **blogs**: Blog posts
- **collections**: Artwork collections
- **promo_codes**: Promotional codes
- **contact_messages**: Contact form submissions
- **faqs**: FAQ entries
- **page_contents**: CMS page content
- **general_settings**: System settings
- **exchange_rates**: Currency exchange rates

### Total Migrations: 68

---

## API Endpoints

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `GET /api/me` - Get current user
- `POST /api/logout` - User logout

### Public API
- `GET /api/artworks` - List artworks
- `GET /api/artworks/{id}` - Get artwork details
- `GET /api/artists` - List artists
- `GET /api/artists/{id}` - Get artist details

### Admin API
- Analytics endpoints
- User management
- Artist management
- Artwork moderation
- Settings management
- Exhibition management
- Blog management
- Collection management
- Commission management
- Payout management
- Shipment management
- Payment proof management
- Promo code management
- Contact message management
- FAQ management
- Page content management
- Exchange rate management
- Role management
- Permission management
- Category management
- Order management

---

## Key Services

### CurrencyService
- Currency conversion (USD, MMK, etc.)
- Exchange rate management
- Currency formatting
- Multi-currency price calculation

### PaymentService
- Payment processing for multiple methods
- Myanmar mobile payment support
- International payment support (Stripe, PayPal)
- Payment validation
- Refund processing

### ShippingService
- Shipping method calculation
- Shipping cost calculation
- Shipment tracking

### EmailService
- Order confirmation emails
- Custom order notifications
- Artist approval notifications

---

## Security Features

- **CSRF Protection**: Enabled on all forms
- **Rate Limiting**: Login attempts throttled
- **Password Hashing**: Laravel's bcrypt
- **SQL Injection Protection**: Eloquent ORM with parameter binding
- **XSS Protection**: Blade template escaping
- **Authorization Policies**: Laravel policies for resource access
- **Middleware**: Role-based access control
- **Email Verification**: Optional email verification
- **Password Reset**: Secure token-based password reset

---

## Frontend Technologies

- **Build Tool**: Vite
- **CSS Framework**: Custom CSS with responsive design
- **JavaScript**: Alpine.js for interactivity
- **Icons**: Custom icons
- **QR Codes**: simple-qrcode package
- **PDF Generation**: laravel-dompdf

---

## Dependencies

### Key Laravel Packages
- `laravel/sanctum` - API authentication
- `laravel/scout` - Full-text search
- `barryvdh/laravel-dompdf` - PDF generation
- `simplesoftwareio/simple-qrcode` - QR code generation

### Other Dependencies
- Various Laravel core packages
- Database drivers
- Cache drivers
- Queue drivers

---

## Environment Configuration

- **Environment**: Local/Production
- **Debug Mode**: Configurable
- **Cache**: Database cache
- **Session**: Database session
- **Queue**: Database queue
- **Mail**: Log driver (configurable)
- **Logs**: Stack/single log driver

---

## File Structure

```
panchigallery.com/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Policies/
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── artist/
│   │   ├── collector/
│   │   ├── auth/
│   │   ├── artworks/
│   │   ├── orders/
│   │   └── ...
│   └── ...
├── routes/
│   ├── web.php
│   └── api.php
├── public/
│   └── storage/
└── config/
```

---

## Development Workflow

### Running the Application
```bash
php artisan serve
```

### Running Migrations
```bash
php artisan migrate
php artisan migrate:status
```

### Clearing Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Optimizing for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## System Status

✅ **All Systems Operational**

- Authentication: Working
- User Roles: Working
- Artwork Management: Working
- Certificate System: Working
- QR Code Generation: Working
- Custom Orders: Working
- Cart & Checkout: Working
- Payment Processing: Working
- Database: All 68 migrations applied
- Server: Running on http://127.0.0.1:8000

---

## Future Enhancements

Potential areas for future development:
- Real-time notifications
- Advanced search with filters
- Artist portfolio customization
- Virtual exhibitions
- Auction system
- Multi-language support
- Mobile app development
- Advanced analytics dashboard
- AI-powered artwork recommendations

---

## Support & Documentation

For detailed documentation on specific features, refer to the respective controller and model files in the application codebase.

---

**Last Updated**: June 1, 2026  
**Version**: 1.0  
**Status**: Production Ready ✅
