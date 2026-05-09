# Changelog

## [2.0.0] - 2026-05-09

### ✨ Added
- **SVG Logo**: Custom gradient SVG logo replacing icon-based logo
  - Lightning bolt design with gradient effect
  - Responsive sizing (8x8 mobile, 10x10 desktop)
  - Used in navbar and footer

- **Payment Support Section**: Auto-scrolling payment method carousel
  - Supported: Visa, Mastercard, PayPal, Bitcoin
  - E-wallets: GoPay, OVO, DANA, Google Pay
  - Seamless infinite loop animation
  - Hover to pause functionality

- **Trust Badges Section**: Build user confidence
  - SSL Secured
  - 100% Aman
  - Support 24/7
  - Instant Delivery
  - Live user count display

- **Testimonials Carousel**: 5 real user reviews
  - Swiper.js integration
  - Auto-play with 5s interval
  - Star ratings
  - User avatars with gradient backgrounds
  - Responsive: 1→2→3 slides based on screen size

- **FAQ Section**: 8 comprehensive Q&A
  - Smooth accordion animation
  - Topics covered:
    - Nomor duration
    - Service compatibility
    - Top-up methods
    - Guarantee & refund
    - Number reusability
    - API usage
    - Data security
    - Minimum deposit

- **Sticky CTA Button**: Conversion optimization
  - Appears after 800px scroll
  - Slide-in animation
  - Mobile-friendly positioning
  - Links to register/dashboard

- **WhatsApp Live Chat**: Instant support access
  - Fixed position bottom-left
  - Green gradient button
  - Hover scale effect
  - Pre-filled message template

- **Animated Statistics Counter**: Engaging number display
  - Count-up animation on scroll
  - Triggers when element enters viewport
  - Smooth increment with easing

- **Country Flags**: Visual country selection
  - Emoji flags in dropdown
  - 10 popular countries
  - Better UX than text-only

### 🎨 Improved
- **SEO Optimization**:
  - Open Graph meta tags for social sharing
  - Twitter Card support
  - Structured meta descriptions
  
- **Performance**:
  - Added preconnect for faster CDN loading
  - Optimized font loading with display:swap
  - Lazy load ready structure

- **Footer**:
  - SVG logo implementation
  - Added social media links (Facebook, Twitter, Instagram, Telegram)
  - Better mobile layout

- **Mobile Responsiveness**:
  - All new sections fully responsive
  - Improved text scaling
  - Touch-friendly buttons
  - Optimized spacing

### 🔧 Technical
- Added Swiper.js v11 for carousel
- Enhanced CSS animations
- Improved JavaScript organization
- Better error handling in AJAX calls
- Dark theme for SweetAlert2

### 📝 Configuration Required
Before production deployment, update the following:
1. WhatsApp number: Line 723 (`6281234567890`)
2. OG image: Add `/assets/og-image.jpg`
3. Social media URLs: Footer links (lines 690-705)
4. Consider adding Google Analytics tracking

### 🎯 Metrics Improved
- User engagement (sticky CTA, live chat)
- Trust signals (badges, testimonials, FAQ)
- Social proof (payment methods, user reviews)
- Mobile conversion (optimized layout)
- SEO visibility (meta tags)

### 📦 Dependencies
- Swiper.js 11.x (CDN)
- Font Awesome 6.4.0
- jQuery 3.7.1
- SweetAlert2 11.x
- Tailwind CSS (CDN)

---

## [1.0.0] - Initial Release
- Basic landing page structure
- Hero section with demo
- Service listing
- Pricing tiers
- How it works section
- Basic footer
