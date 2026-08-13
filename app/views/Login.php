<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ExpenseTracker | Login</title>

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

    <div class="flex w-full max-w-5xl h-auto md:h-[600px] shadow-2xl rounded-2xl overflow-hidden flex-col md:flex-row">

        <div class="flex-1 bg-gradient-to-br from-blue-500 to-blue-900 text-white p-8 md:p-12 flex flex-col justify-center">
            
            <div class="flex items-center mb-8">
                <i class="fas fa-chart-line text-4xl mr-4"></i>
                <h1 class="text-2xl font-bold">ExpenseTracker</h1>
            </div>

            <p class="text-lg mb-10 leading-relaxed opacity-90">
                Take control of your finances. Set monthly budgets, track expenses, and see what’s left.
            </p>

            <ul class="space-y-5">
                <li class="flex items-center">
                    <i class="fas fa-chart-pie w-9 h-9 flex items-center justify-center bg-white/20 rounded-full mr-4"></i>
                    Track monthly budget and spending
                </li>
                <li class="flex items-center">
                    <i class="fas fa-receipt w-9 h-9 flex items-center justify-center bg-white/20 rounded-full mr-4"></i>
                    Manage expenses
                </li>
                <li class="flex items-center">
                    <i class="fas fa-shield-alt w-9 h-9 flex items-center justify-center bg-white/20 rounded-full mr-4"></i>
                    Secure login with email verification
                </li>
            </ul>

        </div>

        <div class="flex-1 bg-white p-8 md:p-12 flex flex-col justify-center relative">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
                <p class="text-gray-500">Sign in to access your expense dashboard</p>
            </div>
            
            <div class="h-6 ">
                <?php if(!empty($login_error['error'])): ?>
                    <div class="px-2 text-red-500 bg-red-100  rounded w-fit mx-auto text-center">
                        <?= $login_error['message'] ?? '' ?>
                    </div>
                <?php endif; ?>
            </div>

            <form class="space-y-2" method="POST" action="login">

                <div>
                    <label class="block mb-2 text-gray-600 font-medium">Email Address</label>
                    <div class="relative ">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input 
                            name="email"
                            type="email"
                            placeholder="Enter your email"
                            value="<?= htmlspecialchars($form_data['email'] ?? '') ?>"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        >
                    </div>
                    <p class="text-sm text-red-500 ml-2 h-5"><?= $validation_errors['email'] ?? '' ?></p>
                </div>

                <div>
                    <label class="block mb-2 text-gray-600 font-medium">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input 
                            name="password"
                            type="password"
                            placeholder="Enter your password"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            autocomplete="off"
                        >
                    </div>
                    <p class="text-sm text-red-500 ml-2 h-5"><?= $validation_errors['password'] ?? '' ?></p>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center space-x-2 text-gray-600">
                        <input name="remember" type="checkbox" class="accent-blue-600">
                        <span>Remember me</span>
                    </label>

                    <a href="forgot_password" class="text-blue-600 font-medium hover:underline">
                        Forgot password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-full py-4 rounded-xl text-lg font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-900 hover:shadow-lg transition">
                    Sign In
                </button>

                <p class="text-center text-gray-500">
                    Don't have an account?
                    <a href="signup" class="text-blue-600 font-medium hover:underline">
                        Sign up here
                    </a>
                </p>
            </form>
        </div>
    </div>

<script src="<?= $appUrl ?>/js/app.js"></script>

</body>
</html>
