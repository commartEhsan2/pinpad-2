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

## Development
### File Structure
- **commart-pinpad.php**: Main plugin file.
- **includes/**: Contains additional PHP files for handling AJAX requests, security, and admin settings.
- **css/**: Contains the main stylesheet for the Pinpad interface.
- **js/**: Contains the main JavaScript file for handling Pinpad interactions.

## License
This project is licensed under the GPLv2 License - see the [LICENSE](LICENSE) file for details.

## Author
Ehsan Pihadi

For any issues or feature requests, please open an issue on the GitHub repository.
