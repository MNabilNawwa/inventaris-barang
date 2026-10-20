<?php
require_once __DIR__ . '/includes/init.php';

if (!empty($_SESSION['login'])) {
    redirect('index.php');
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!csrf_valid()) {
        $error = t('err_csrf');
    } elseif ($username === '' || $password === '') {
        $error = t('err_login_empty');
    } else {
        $stmt = $conn->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);          // cegah session fixation
            $_SESSION['login']    = true;
            $_SESSION['user_id']  = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            redirect('index.php');
        } else {
            $error = t('err_login_wrong');
        }
    }
}

$judul_halaman = t('login_title');
$tampil_nav = false;
$body_class = 'auth';
include __DIR__ . '/includes/header.php';
?>
<section class="card card-narrow">
    <span class="brand-mark brand-mark-lg"><?= icon('box') ?></span>
    <h1><?= e(t('login_title')) ?></h1>
    <p class="muted"><?= e(t('login_sub')) ?></p>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error" role="alert"><?= icon('alert') ?><span><?= e($error) ?></span></div>
    <?php endif; ?>

    <form method="POST" action="login.php" class="form" novalidate>
        <?= csrf_field() ?>
        <div class="field">
            <label for="username"><?= e(t('username')) ?></label>
            <input type="text" id="username" name="username" autocomplete="username" value="<?= e($username) ?>" autofocus>
        </div>
        <div class="field">
            <label for="password"><?= e(t('password')) ?></label>
            <input type="password" id="password" name="password" autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary btn-block"><?= e(t('btn_login')) ?></button>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
