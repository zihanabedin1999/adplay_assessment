# adplay_assessment

## Overview

This is a PHP-based web application for managing marketing campaigns. The system allows users to:
- View active campaigns
- Add new campaigns
- Delete existing campaigns
- Generate reports on campaign performance
- Reconcile campaign data

## Database Structure

The system uses a MySQL database with a single table `campaigns` that contains the following fields:
- `id` - Unique identifier
- `campaigncode` - Campaign code (unique)
- `campaign_name` - Name of the campaign
- `user_id` - ID of the user who created the campaign
- `fixed_price` - Fixed price per show
- `campaignshow` - Number of shows
- `created_at` - Timestamp of creation
- `updated_at` - Timestamp of last update

## File Structure

```
marketing_db.sql        # Database schema and sample data
config.php              # Database connection configuration
index.php              # Main dashboard showing active campaigns
add_campaign.php       # Form to add new campaigns
delete_campaign.php    # Form to delete campaigns
report_dashboard.php   # Campaign reports dashboard
underperforming_campaigns.php # Report for underperforming campaigns
campaign_reconciliation.php # Campaign reconciliation report
style.css              # Main stylesheet
report_styles.css      # Styles for report pages
style3.css             # Additional styles
```

## Installation

1. Import the database schema:
   ```bash
   mysql -u username -p marketing_db < marketing_db.sql
   ```

2. Configure database connection in `config.php`:
   ```php
   $host = 'localhost';
   $db = 'marketing_db';
   $user = 'root';
   $pass = '';
   ```

3. Place all files in your web server's document root (e.g., `/var/www/html`)

4. Access the application through your web browser

## Features

### Main Dashboard
- Displays all active campaigns
- Shows campaign code, name, fixed price, shows, and total spend
- Provides quick access to all system functions

### Campaign Management
- **Add Campaign**: Simple form to create new campaigns
- **Delete Campaign**: Remove campaigns by their campaign code

### Reporting
- **Underperforming Campaigns**: Identify campaigns spending less than 50% of budget
- **Campaign Reconciliation**: View campaigns grouped by user

## Technical Stack
- PHP
- MySQL
- HTML/CSS
- Vanilla JavaScript

## Usage

1. Access the main dashboard (`index.php`)
2. Use the navigation buttons to:
   - Add new campaigns
   - Delete existing campaigns
   - View reports
   - Reconcile campaign data

## Screenshots

(Include screenshots of the main interface and key features if available)

## License

This project is open-source and available under the MIT License.
