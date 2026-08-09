<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ExpenseTracker | Email Verification</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?=  $appUrl ?>/css/app.css">
<link rel="icon" type="image/svg+xml" href="<?= $appUrl ?>/den-icon.svg">

</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-5">

<!-- Flash message -->
<div class="w-full flex items-center justify-center absolute top-4 z-50">
    <?php if(!empty($_SESSION['flash'])) : ?>
        <?php $flash = $_SESSION['flash']; ?>
            <div 
                id="flash"
                class="flex items-start sm:items-center gap-2 p-4 text-sm shadow-sm rounded-md font-semibold w-80 transition-all translate-y-0
                    <?= $flash['type'] === 'success'
                        ? 'bg-white text-green-700' 
                        : 'bg-red-200 text-red-700' 
                    ?>
                "
            >
                <i class="fa-solid fa-circle-info"></i>
                <?= $flash['message'] ?>    
            </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
</div>

<div id="default-display" 
    class="max-w-[440px] w-full bg-white rounded-[32px] shadow-[0_20px_35px_-8px_rgba(0,20,45,0.2)] p-10 text-center space-y-6">

    <div class="flex justify-center">
        <div class="w-20 h-20 flex items-center justify-center rounded-full bg-blue-50">
            <i class="fas fa-envelope-open-text text-3xl text-blue-500"></i>
        </div>
    </div>

    <div class="space-y-2">
        <h2 class="text-xl font-semibold text-slate-900">
            Verify your email
        </h2>
        <p class="text-sm text-slate-500 leading-relaxed">
            We’ve sent a verification link to your email address.  
            Please check your inbox and follow the instructions.
        </p>
    </div>

    <div class="flex justify-center">
        <div class="bg-blue-50 text-blue-900 rounded-full px-4 py-2 text-sm inline-flex items-center gap-2">
            <i class="fa fa-triangle-exclamation text-blue-500"></i>
            Check your spam folder
        </div>
    </div>

    <div class="border-t border-gray-100"></div>

    <div class="space-y-3">
        <p class="text-sm text-slate-500">
            Didn’t receive the verification link?
        </p>

        <button onclick="openModal('resend-verification-form')" 
            class="w-full py-3 rounded-xl text-sm font-semibold text-blue-700 border border-blue-200 hover:bg-blue-50 transition">
            Resend Verification
        </button>
    </div>

</div>


<!-- Resend Modal -->
<div id="resend-verification-form" 
    class="fixed inset-0 bg-black/30 flex items-center justify-center hidden z-50">

    <div class="max-w-[440px] w-full bg-white rounded-[32px] shadow-[0_20px_35px_-8px_rgba(0,20,45,0.2)] p-10 relative space-y-6">

        <button onclick="closeModal('resend-verification-form')"
            class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 text-xl">
            &times;
        </button>

        <div>
            <h2 class="text-3xl font-semibold text-slate-900">
                Resend Verification Link
            </h2>
            <div class="text-slate-500 text-sm mb-8 leading-relaxed border-l-4 border-blue-200 pl-4 mt-1">
                Enter your email to receive a new link
            </div>
        </div>

        <form method="POST" action="resend_verification" class="space-y-7">
            <div class="relative">
                <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input
                    name="email"
                    type="email"
                    placeholder="Your email address"
                    required
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
            </div>

            <button type="submit"
                class="w-full py-4 rounded-xl text-lg font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-900 hover:shadow-lg transition flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i>
                Resend link
            </button>
        </form>
    </div>
</div>

<script src="<?= $appUrl ?>/js/app.js"></script>

</body>
</html>