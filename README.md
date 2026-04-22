# FuntrekRD - Dominican Travel System

## Overview
FuntrekRD is a professional web-based management system for travel agencies. Developed with an MVC architecture in PHP, it provides a robust suite of tools for managing tourist packages, excursions, transfers, and professional quotations.

## Features
- **Professional Quotation Module**: Create detailed, itemized quotes with dynamic tax calculations.
- **Airport Sign Tool**: Standalone tool for generating airport welcome signs with editable fields.
- **Progressive Web App (PWA)**: Installable on Android and iOS devices for an app-like experience.
- **Administrative Dashboard**: Real-time stats, booking management, and custom request tracking.
- **Multi-language Support**: Fully translatable (English/Spanish).
- **Security**: SQL Injection prevention, CSRF protection, and BCRYPT password hashing.

## Installation
1. Clone the repository.
2. Create a MySQL database and import the schema from `config/dominican_travel_db.sql`.
3. Copy `config/config.sample.php` to `config/config.php`.
4. Configure your database credentials and `APP_URL` in `config/config.php`.
5. Ensure the `public/` directory is your web root or configure `.htaccess`.

## Technical Specs
- **Backend**: PHP 8.1+
- **Database**: MySQL / MariaDB
- **Frontend**: Vanilla CSS, JavaScript (ES6+), FontAwesome.

## License
**Proprietary / No Commercial Use**

Copyright (c) 2026 Starlin (FuntrekRD). All rights reserved.

This software is provided for evaluation purposes. Commercial use, redistribution, or modification for commercial profit is strictly prohibited without explicit written permission and purchase of a commercial license.
