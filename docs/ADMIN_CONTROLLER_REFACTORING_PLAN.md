# AdminController Refactoring Plan

**Current Status:** AdminController.php is 106KB with 2,775 lines - needs immediate refactoring

## Current AdminController Responsibilities

The AdminController currently handles **20+ different domains**:

1. **Dashboard** - Statistics, analytics, system health
2. **User Management** - CRUD, approval, status, impersonation
3. **Artist Management** - Approval, editing, deletion
4. **Artwork Management** - CRUD, approval, moderation
5. **Exhibition Management** - CRUD
6. **Blog Management** - CRUD
7. **Marketplace Management** - Listings, approvals
8. **Category Management** - CRUD
9. **Collection Management** - CRUD
10. **Support Management** - Contact messages, FAQs
11. **Transaction Management** - CRUD, refunds
12. **Order Management** - CRUD, shipping, cancellation
13. **Shipment Management** - CRUD, tracking
14. **Payment Methods Management** - CRUD, activation
15. **Commissions Management** - CRUD, mark paid
16. **Wishlists Management** - View, delete
17. **FAQs Management** - CRUD, publish/unpublish
18. **Payment Verification** - Approve/reject proofs
19. **Payout Management** - CRUD, process, reject
20. **Reports Management** - Dashboard, sales, users, artworks, marketplace, customers, financial, export
21. **Settings Management** - Site settings
22. **Page Content Management** - CRUD for CMS pages
23. **Frontend Management** - Homepage, navigation

## Proposed Controller Structure

### 1. AdminDashboardController
**Responsibilities:**
- Dashboard statistics
- Analytics overview
- System health monitoring
- Sales charts data

**Methods:**
- `dashboard()` - Main dashboard view
- `statistics()` - Detailed statistics
- `getDiskUsage()` - Helper method
- `formatBytes()` - Helper method

---

### 2. AdminUserController
**Responsibilities:**
- User CRUD operations
- User approval workflow
- User status management
- User impersonation

**Methods:**
- `users()` - List users
- `createUser()` - Create user form
- `storeUser()` - Store new user
- `pendingUsers()` - Pending approvals
- `showUser()` - Show user details
- `approveUser()` - Approve user
- `rejectUser()` - Reject user
- `editUser()` - Edit user form
- `updateUser()` - Update user
- `deleteUser()` - Delete user
- `bulkApproveUsers()` - Bulk approval
- `bulkDeleteUsers()` - Bulk deletion
- `exportUsers()` - Export users
- `resendVerificationEmail()` - Resend verification
- `toggleUserStatus()` - Toggle active status
- `impersonateUser()` - Impersonate user
- `stopImpersonation()` - Stop impersonation

---

### 3. AdminArtistController
**Responsibilities:**
- Artist management
- Artist approval workflow
- Artist profile management

**Methods:**
- `artists()` - List artists
- `pendingArtists()` - Pending approvals
- `approveArtist()` - Approve artist
- `rejectArtist()` - Reject artist
- `editArtist()` - Edit artist form
- `updateArtist()` - Update artist
- `deleteArtist()` - Delete artist

---

### 4. AdminArtworkController
**Responsibilities:**
- Artwork CRUD operations
- Artwork approval workflow
- Artwork moderation

**Methods:**
- `artworks()` - List artworks
- `createArtwork()` - Create artwork form
- `storeArtwork()` - Store new artwork
- `pendingArtworks()` - Pending approvals
- `approveArtwork()` - Approve artwork
- `rejectArtwork()` - Reject artwork
- `editArtwork()` - Edit artwork form
- `showArtwork()` - Show artwork details
- `updateArtwork()` - Update artwork
- `deleteArtwork()` - Delete artwork

---

### 5. AdminExhibitionController
**Responsibilities:**
- Exhibition CRUD operations
- Exhibition management

**Methods:**
- `exhibitions()` - List exhibitions (already exists)
- `create()` - Create exhibition form (already exists)
- `store()` - Store exhibition (already exists)
- `edit()` - Edit exhibition form (already exists)
- `update()` - Update exhibition (already exists)
- `destroy()` - Delete exhibition (already exists)

---

### 6. AdminBlogController
**Responsibilities:**
- Blog CRUD operations
- Blog management

**Methods:**
- `blogs()` - List blogs (already exists)
- `create()` - Create blog form (already exists)
- `store()` - Store blog (already exists)
- `edit()` - Edit blog form (already exists)
- `update()` - Update blog (already exists)
- `destroy()` - Delete blog (already exists)

---

### 7. AdminMarketplaceController
**Responsibilities:**
- Marketplace listings management
- Resale approval workflow

**Methods:**
- `marketplace()` - Marketplace overview
- `marketplaceListings()` - All listings
- `pendingMarketplace()` - Pending approvals
- `approveResale()` - Approve resale
- `rejectResale()` - Reject resale
- `resales()` - Resale management

---

### 8. AdminCategoryController
**Responsibilities:**
- Category CRUD operations
- Category management

**Methods:**
- `categories()` - List categories
- `createCategory()` - Create category form
- `storeCategory()` - Store new category
- `editCategory()` - Edit category form
- `updateCategory()` - Update category
- `deleteCategory()` - Delete category

---

### 9. AdminCollectionController
**Responsibilities:**
- Collection CRUD operations
- Collection management

**Methods:**
- `collections()` - List collections
- `createCollection()` - Create collection form
- `storeCollection()` - Store new collection
- `editCollection()` - Edit collection form
- `updateCollection()` - Update collection
- `deleteCollection()` - Delete collection

---

### 10. AdminSupportController
**Responsibilities:**
- Contact message management
- FAQ management
- Support tickets

**Methods:**
- `support()` - Support overview
- `supportContacts()` - Contact messages
- `markContactsRead()` - Mark as read
- `exportContacts()` - Export contacts
- `showContact()` - Show contact details
- `respondContact()` - Respond to contact
- `resolveContact()` - Resolve contact
- `deleteContact()` - Delete contact
- `supportFaq()` - FAQ management
- `createFaq()` - Create FAQ form
- `storeFaq()` - Store new FAQ
- `editFaq()` - Edit FAQ form
- `updateFaq()` - Update FAQ
- `deleteFaq()` - Delete FAQ

---

### 11. AdminTransactionController
**Responsibilities:**
- Transaction management
- Transaction refunds

**Methods:**
- `transactions()` - List transactions
- `pendingTransactions()` - Pending transactions
- `showTransaction()` - Show transaction details
- `refundTransaction()` - Refund transaction

---

### 12. AdminOrderController
**Responsibilities:**
- Order management
- Order processing
- Order cancellation

**Methods:**
- `orders()` - List orders
- `showOrder()` - Show order details
- `editOrder()` - Edit order form
- `updateOrder()` - Update order
- `shipOrder()` - Create shipment
- `cancelOrder()` - Cancel order

---

### 13. AdminShipmentController
**Responsibilities:**
- Shipment management
- Shipment tracking

**Methods:**
- `shipments()` - List shipments
- `showShipment()` - Show shipment details
- `editShipment()` - Edit shipment form
- `updateShipment()` - Update shipment
- `trackShipment()` - Track shipment

---

### 14. AdminPaymentMethodController
**Responsibilities:**
- Payment method management
- Payment method activation

**Methods:**
- `paymentMethods()` - List payment methods
- `createPaymentMethod()` - Create payment method form
- `storePaymentMethod()` - Store new payment method
- `editPaymentMethod()` - Edit payment method form
- `updatePaymentMethod()` - Update payment method
- `deletePaymentMethod()` - Delete payment method
- `activatePaymentMethod()` - Activate payment method
- `deactivatePaymentMethod()` - Deactivate payment method

---

### 15. AdminCommissionController
**Responsibilities:**
- Commission management
- Commission payment tracking

**Methods:**
- `commissions()` - List commissions
- `showCommission()` - Show commission details
- `markCommissionPaid()` - Mark commission as paid

---

### 16. AdminWishlistController
**Responsibilities:**
- Wishlist management
- Wishlist analytics

**Methods:**
- `wishlists()` - List wishlists
- `deleteWishlist()` - Delete wishlist

---

### 17. AdminFaqController
**Responsibilities:**
- FAQ management
- FAQ publishing

**Methods:**
- `faqs()` - List FAQs
- `createFaq()` - Create FAQ form
- `storeFaq()` - Store new FAQ
- `editFaq()` - Edit FAQ form
- `updateFaq()` - Update FAQ
- `deleteFaq()` - Delete FAQ
- `publishFaq()` - Publish FAQ
- `unpublishFaq()` - Unpublish FAQ

---

### 18. AdminPaymentVerificationController
**Responsibilities:**
- Payment proof verification
- Payment approval workflow

**Methods:**
- `paymentVerifications()` - List payment verifications
- `approvePayment()` - Approve payment proof
- `rejectPayment()` - Reject payment proof

---

### 19. AdminPayoutController
**Responsibilities:**
- Payout management
- Payout processing

**Methods:**
- `payouts()` - List payouts (already exists)
- `pendingPayouts()` - Pending payouts (already exists)
- `showPayout()` - Show payout details (already exists)
- `processPayout()` - Process payout (already exists)
- `rejectPayout()` - Reject payout (already exists)
- `revenue()` - Revenue overview (already exists)
- `updateFeePercentage()` - Update fee percentage (already exists)

---

### 20. AdminReportController
**Responsibilities:**
- Report generation
- Report analytics
- Report exports

**Methods:**
- `reportsDashboard()` - Reports dashboard (already exists)
- `salesReport()` - Sales report (already exists)
- `usersReport()` - Users report (already exists)
- `artworksReport()` - Artworks report (already exists)
- `marketplaceReport()` - Marketplace report (already exists)
- `customersReport()` - Customers report (already exists)
- `financialReport()` - Financial report (already exists)
- `exportReport()` - Export report (already exists)

---

### 21. AdminSettingsController
**Responsibilities:**
- Site settings management
- Configuration management

**Methods:**
- `settings()` - Settings overview (already exists)
- `updateSiteSettings()` - Update site settings (already exists)

---

### 22. AdminPageContentController
**Responsibilities:**
- CMS page content management
- Page content CRUD

**Methods:**
- `pageContents()` - List page contents (already exists)
- `createPageContent()` - Create page content form (already exists)
- `storePageContent()` - Store new page content (already exists)
- `editPageContent()` - Edit page content form (already exists)
- `updatePageContent()` - Update page content (already exists)
- `destroyPageContent()` - Delete page content (already exists)
- `bulkUpdatePageContent()` - Bulk update page content (already exists)

---

### 23. AdminFrontendController
**Responsibilities:**
- Frontend management
- Homepage configuration
- Navigation management

**Methods:**
- `frontendDashboard()` - Frontend dashboard (already exists)
- `homepage()` - Homepage management (already exists)
- `updateHomepageSection()` - Update homepage section (already exists)
- `navigation()` - Navigation management (already exists)
- `updateNavigation()` - Update navigation (already exists)

---

## Implementation Strategy

### Phase 1: Extract Existing Controllers (Already Done)
- ✅ AdminExhibitionController (already exists)
- ✅ AdminBlogController (already exists)
- ✅ AdminPayoutController (already exists)
- ✅ AdminReportController (already exists)
- ✅ AdminPageContentController (already exists)
- ✅ AdminFrontendController (already exists)

### Phase 2: Create New Controllers (Priority Order)
1. **AdminDashboardController** - Extract dashboard logic
2. **AdminUserController** - Extract user management
3. **AdminArtistController** - Extract artist management
4. **AdminArtworkController** - Extract artwork management
5. **AdminCategoryController** - Extract category management
6. **AdminCollectionController** - Extract collection management
7. **AdminSupportController** - Extract support management
8. **AdminTransactionController** - Extract transaction management
9. **AdminOrderController** - Extract order management
10. **AdminShipmentController** - Extract shipment management
11. **AdminPaymentMethodController** - Extract payment method management
12. **AdminCommissionController** - Extract commission management
13. **AdminWishlistController** - Extract wishlist management
14. **AdminFaqController** - Extract FAQ management
15. **AdminPaymentVerificationController** - Extract payment verification
16. **AdminMarketplaceController** - Extract marketplace management

### Phase 3: Update Routes
- Update `routes/web.php` to use new controllers
- Maintain backward compatibility with old routes
- Test all routes

### Phase 4: Update Views
- Update view references to new controllers
- Ensure all views work correctly
- Test all admin functionality

### Phase 5: Remove Old AdminController
- After verification, remove old AdminController
- Clean up unused code

---

## Benefits of Refactoring

1. **Single Responsibility Principle** - Each controller handles one domain
2. **Maintainability** - Easier to find and modify code
3. **Testability** - Easier to write unit tests for individual controllers
4. **Performance** - Smaller controllers load faster
5. **Collaboration** - Multiple developers can work on different controllers simultaneously
6. **Code Reusability** - Common logic can be extracted to services
7. **Security** - Easier to apply role-based access control per controller

---

## Estimated Timeline

- **Phase 1:** ✅ Complete (already done)
- **Phase 2:** 2-3 days (16 controllers to create)
- **Phase 3:** 1 day (route updates)
- **Phase 4:** 1-2 days (view updates and testing)
- **Phase 5:** 0.5 day (cleanup)

**Total Estimated Time:** 4.5-6.5 days

---

## Next Steps

1. Start with AdminDashboardController (highest priority)
2. Move to AdminUserController (most complex)
3. Continue with remaining controllers in priority order
4. Update routes after all controllers are created
5. Test thoroughly before removing old controller

---

**Status:** Ready to begin Phase 2 implementation
