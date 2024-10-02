<?php
// Ограничение количества попыток входа
function limit_login_attempts() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_name = 'login_attempts_' . $ip;

    // Получаем количество попыток входа для текущего IP
    $attempts = get_transient($transient_name);

    if ($attempts === false) {
        // Если попыток ещё не было, устанавливаем в 1
        $attempts = 1;
    } else {
        // Увеличиваем количество попыток
        $attempts++;
    }

    // Если превышен лимит попыток (например, 5)
    if ($attempts >= 5) {
        $lockout_time = 30 * MINUTE_IN_SECONDS; // Блокируем на 30 минут
        set_transient($transient_name, $attempts, $lockout_time);
        wp_die('Превышено количество попыток входа. Попробуйте снова через 30 минут.');
    } else {
        // Обновляем количество попыток
        set_transient($transient_name, $attempts, 30 * MINUTE_IN_SECONDS);
    }
}
add_action('wp_login_failed', 'limit_login_attempts');
?>
