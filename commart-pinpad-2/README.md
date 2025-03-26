# Commart Pinpad Plugin

## Description
Commart Pinpad is a WordPress plugin that provides a one-step login and registration feature using a Pinpad interface. Users can enter their mobile number through the Pinpad, and the plugin will automatically generate a username, password, and email, filling in the respective fields and performing login or registration via AJAX.

## Features
- **Pinpad Interface**: Users can enter their mobile number through an interactive Pinpad.
- **Automatic Field Filling**: The plugin automatically generates usernames, passwords, and emails based on the entered mobile number.
- **AJAX Requests**: Login and registration requests are handled via AJAX for a seamless user experience.
- **Swap Functionality**: Users can switch between login and registration forms using a swap button.
- **Security Measures**: The plugin includes rate limiting and IP blocking for security.
- **Admin Settings**: Customize the plugin's behavior and appearance through the WordPress admin panel.

## Installation
1. Download the plugin files and upload them to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure the plugin settings in the WordPress admin panel under 'Commart Pinpad'.

## Shortcodes
- `[commart_pinpad]`: Use this shortcode to display the Pinpad interface on any post or page.

## Admin Settings
- **Default Username**: Set a default prefix for generated usernames.
- **Custom CSS**: Add custom CSS styles for the Pinpad interface.
- **Show Errors**: Enable or disable the display of error messages.
- **Script Priority**: Set the priority of the plugin's scripts to resolve potential conflicts.

## Security
- The plugin includes measures to prevent brute force attacks and unauthorized access.
- Mobile numbers are validated to ensure they start with '09'.
- Users are blocked after repeated invalid attempts.

## Development
### File Structure
- **commart-pinpad.php**: Main plugin file.
- **includes/**: Contains additional PHP files for handling AJAX requests, security, and admin settings.
- **css/**: Contains the main stylesheet for the Pinpad interface.
- **js/**: Contains the main JavaScript file for handling Pinpad interactions.

### Functions and Hooks
- **AJAX Handlers**: Handles login and registration requests (`includes/ajax-handlers.php`).
- **Security**: Implements security measures such as rate limiting and IP blocking (`includes/security.php`).
- **Admin Settings**: Provides an interface for customizing the plugin's behavior and appearance (`includes/admin-settings.php`).
- **Messages**: Manages customizable messages displayed to users (`includes/messages.php`).

## License
This project is licensed under the GPLv2 License - see the [LICENSE](LICENSE) file for details.

## Author
Ehsan Pihadi

For any issues or feature requests, please open an issue on the GitHub repository.
