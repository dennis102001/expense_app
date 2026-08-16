<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ExpenseTracker | Reset Password</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?=  $appUrl ?>/css/app.css">
<link rel="icon" type="image/svg+xml" href="<?= $appUrl ?>/den-icon.svg">

</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-5">

<!-- Flash message -->
<div class="pointer-events-none fixed left-1/2 top-4 z-50 w-full -translate-x-1/2 px-4">
    <?php if(!empty($_SESSION['flash'])) : ?>
        <?php $flash = $_SESSION['flash']; ?>

            <div 
                id="flash"
                class="pointer-events-auto mx-auto flex w-full max-w-sm items-center gap-3 rounded-xl border px-4 py-3 shadow-lg transition-all duration-500
                    <?= $flash['type'] === 'success'
                        ? 'border-green-200 bg-white text-green-700' 
                        : 'border-red-200 bg-white text-red-700' 
                    ?>
                "
            >
                <!-- Icon -->
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full
                        <?= $flash['type'] === 'success'
                            ? 'bg-green-100 text-green-600'
                            : 'bg-red-100 text-red-600'
                        ?>"
                >
                    <i class="fa-solid <?= $flash['type'] === 'success'
                        ? 'fa-check'
                        : 'fa-xmark'
                    ?>"></i>
                </div>

                <!-- Message -->
                <p class="text-sm font-medium leading-5">
                    <?= htmlspecialchars($flash['message']) ?>
                </p> 
            </div>

        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
</div>

<div class="max-w-[440px] w-full bg-white rounded-[32px] shadow-[0_20px_35px_-8px_rgba(0,20,45,0.2)] p-10">

    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-6 text-blue-900 text-2xl">
        <i class="fas fa-lock-open"></i>
    </div>

    <h2 class="text-3xl font-semibold text-slate-900 mb-2">
        Reset your password
    </h2>

    <div class="text-slate-500 text-sm mb-8 leading-relaxed border-l-4 border-blue-200 pl-4">
        Enter a new password for your account.
    </div>

    <form method="POST" action="reset_password">
        <input type="hidden" name="token" value="<?= $token?>">
        <div class="mb-2 relative">
            <i class="fas fa-lock absolute left-5 top-4  text-slate-400"></i>
            <input
                name="password"
                type="password"
                placeholder="New password"
                required
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
            <p class="text-sm text-red-500 ml-2 h-5"><?= $validation_errors['password'] ?? '' ?></p>
        </div>

        <div class="mb-2 relative">
            <i class="fas fa-lock absolute left-5 top-4  text-slate-400"></i>
            <input
                name="password_confirmation"
                type="password"
                placeholder="Confirm new password"
                required
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
            <p class="text-sm text-red-500 ml-2 h-5"><?= $validation_errors['password_confirmation'] ?? '' ?></p>
        </div>

        <button 
            type="submit"
            data-loading-text="Updating..."
            class="w-full py-4 rounded-xl text-lg font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-900 hover:shadow-lg transition mb-7 disabled:cursor-not-allowed disabled:opacity-60"
        >
            <i class="fas fa-paper-plane"></i>
            Reset Password
        </button>
    </form>

    <div class="flex justify-center">
        <div class="bg-blue-50 text-blue-900 rounded-full px-4 py-2 text-sm inline-flex items-center gap-2">
            <i class="far fa-clock text-blue-500"></i>
            Link expires in 1 hour
        </div>
    </div>

    <hr class="my-6 border-slate-200">

    <div class="flex flex-col items-center gap-3">
        <a href="login" class="text-blue-700 text-sm font-medium flex items-center gap-2  pb-1 hover:text-blue-900 hover:border-blue-700 transition">
            <i class="fas fa-arrow-left"></i>
            Back to sign in
        </a>
    </div>

</div>

<script src="<?= $appUrl ?>/js/app.js"></script>

</body>
</html>