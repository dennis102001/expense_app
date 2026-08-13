<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseTracker | Sign Up</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?=  $appUrl ?>/css/app.css">
    <link rel="icon" type="image/svg+xml" href="<?= $appUrl ?>/den-icon.svg">

</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-5">

<div class="flex w-full max-w-5xl h-fit shadow-2xl rounded-2xl overflow-hidden flex-col md:flex-row">

    <div class="flex-1 bg-gradient-to-br from-blue-500 to-blue-900 text-white p-8 md:p-12 flex flex-col justify-center">

        <div class="flex items-center mb-8">
            <i class="fas fa-user-plus text-4xl mr-4"></i>
            <h1 class="text-2xl font-bold">ExpenseTracker</h1>
        </div>

        <p class="text-lg mb-10 leading-relaxed opacity-90">
            Start your financial journey today. Take control of your finances. Set monthly budgets, track expenses, and see what’s left.
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

    <div class="flex-1 bg-white p-8 md:p-12 flex flex-col justify-center">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Create Account</h2>
            <p class="text-gray-500">Sign up to start tracking your expenses</p>
        </div>

        <div class="h-6 ">
            <?php if(!empty($registration_error['error'])): ?>
                <div class="px-2 text-red-500 bg-red-100  rounded w-fit mx-auto text-center">
                    <?= $registration_error['message'] ?? '' ?>
                </div>
            <?php endif; ?>
        </div>

        <form class="space-y-1" method="POST" action="signup">

            <div>
                <label class="block mb-2 text-gray-600 font-medium">Full Name</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input 
                        name="name"
                        type="text"
                        placeholder="Enter your full name"
                        value="<?= htmlspecialchars($form_data['name'] ?? '') ?>"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <p class="text-sm text-red-500 ml-2 h-5"><?= $validation_errors['name'] ?? '' ?></p>
            </div>

            <div>
                <label class="block mb-2 text-gray-600 font-medium">Email Address</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input 
                        name="email"
                        type="email"
                        placeholder="Enter your email"
                        value="<?= htmlspecialchars($form_data['email'] ?? '') ?>"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
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
                        placeholder="Create a password"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        autocomplete="off"
                    >
                </div>
                <p class="text-sm text-red-500 ml-2 h-5"><?= $validation_errors['password'] ?? '' ?></p>
            </div>

            <div>
                <label class="block mb-2 text-gray-600 font-medium">Confirm Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input 
                        name="password_confirmation"
                        type="password"
                        placeholder="Confirm your password"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        autocomplete="off"
                    >
                </div>
                <p class="text-sm text-red-500 ml-2 h-5"><?= $validation_errors['password_confirmation'] ?? '' ?></p>
            </div>

            <button
                type="submit"
                class="w-full py-4 rounded-xl text-lg font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-900 hover:shadow-lg transition">
                Create Account
            </button>

            <p class="text-center text-gray-500">
                Already have an account?
                <a href="login" class="text-blue-600 font-medium hover:underline">
                    Sign in
                </a>
            </p>

        </form>
    </div>
</div>

</body>
</html>
