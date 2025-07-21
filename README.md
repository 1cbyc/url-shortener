# URL Shortener

PHP URL shortener with modular architecture, robust security, analytics, and a polished web interface. Designed for real-world deployment and extensibility.

## Features
- Shorten long URLs with unique, secure codes
- Persistent storage with database support
- Analytics and click tracking
- User authentication (optional)
- Custom short codes
- Expiry and deactivation of links
- API for programmatic access
- Responsive, modern web interface
- Admin dashboard

## Getting Started

### Requirements
- PHP 8.1+
- Composer
- SQLite or MySQL
- Web server (Apache, Nginx, or PHP built-in)

### Installation
1. Clone my repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your environment
4. Set up your web server to serve the `public/` directory
5. Run database migrations (instructions in docs)

### Documentation
See `docs/whats-next.md` for the project roadmap and `docs/explanation.md` for architecture and design details.
