<?php
    // print_r($credentials);
?>
    <label for="account_id">Account ID:</label>
    <input type="text" id="account_id" name="account_id" value="<?php echo (!empty($credentials["account_id"]))?esc_html($credentials["account_id"]):""; ?>" required>

    <label for="auth_token">Auth Token:</label>
    <input type="password" id="auth_token" name="auth_token" value="<?php echo (!empty($credentials["auth_token"]))?esc_html($credentials["auth_token"]):""; ?>" required>

