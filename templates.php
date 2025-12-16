<?php
/**
 * Editor Template: Tạo ô nhập liệu tự động
 */
function editorFor($name, $label, $type = "text", $value = "") {
    echo '<div style="margin-bottom: 15px;">';
    echo '    <label style="display: block; margin-bottom: 6px; color: #444;">' . $label . '</label>';
    echo '    <input type="' . $type . '" name="' . $name . '" value="' . htmlspecialchars($value) . '" required 
                style="width: 100%; padding: 10px; border: 1px solid #e0c77f; border-radius: 8px; box-sizing: border-box;">';
    echo '</div>';
}

/**
 * Display Template: Hiển thị lỗi (Màu đỏ)
 */
function displayError($message) {
    if ($message) {
        echo '<div class="error-msg" style="color: red; background: #ffe6e6; padding: 10px; margin-bottom: 15px; text-align: center; border-radius: 5px;">' . $message . '</div>';
    }
}

/**
 * Display Template: Hiển thị thành công (Màu xanh)
 */
function displaySuccess($message) {
    if ($message) {
        echo '<div class="success-msg" style="color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 10px; margin-bottom: 15px; text-align: center; border-radius: 5px;">' . $message . '</div>';
    }
}
?>