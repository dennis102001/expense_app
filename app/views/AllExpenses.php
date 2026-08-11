<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ExpenseTracker | All Expenses</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?=  $appUrl ?>/css/app.css">
<link rel="icon" type="image/svg+xml" href="<?= $appUrl ?>/den-icon.svg">

</head>
<body class="bg-gray-100 min-h-screen">

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

<!-- Header -->
<header class="bg-gradient-to-r from-blue-500 to-blue-900 text-white p-5 shadow-lg sticky top-0 z-40">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold flex items-center gap-3">
            <i class="fas fa-chart-line"></i> ExpenseTracker Dashboard
        </h1>
        <button type="button" command="show-modal" commandfor="logout-modal" class="bg-white text-blue-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200">
            Logout
        </button>
    </div>
</header>

<div class="max-w-6xl mx-auto p-6 space-y-6">

    <a href="dashboard" class="bg-white border border-blue-500 text-blue-700 p-3 rounded-xl font-semibold hover:bg-blue-50 flex items-center justify-center gap-3 w-fit">
        <i class="fas fa-chevron-left"></i> Go to dashboard
    </a>

    <div class="bg-white rounded-2xl shadow p-6">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">All Expenses</h3>
            <a href="print" target="_blank" class="bg-white border border-blue-500 text-blue-700 p-3 rounded-xl font-semibold hover:bg-blue-50 flex items-center justify-center gap-3 w-fit">
                <i class="fas fa-print"></i> Print
            </a>
        </div>

        <!-- List of Expenses for LG screens -->
        <div class="overflow-x-auto hidden md:block border-b">
            <table class="w-full text-left border-collapse ">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm">
                        <th class="p-3  w-1/6 ">Date</th>
                        <th class="p-3 w-2/6 ">Description</th>
                        <th class="p-3 w-1/6 ">Category</th>
                        <th class="p-3 w-1/6  text-right">Amount</th>
                        <th class="p-3 w-1/6  text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <?php foreach($expenses as $expense) : ?>
                        <tr>
                            <td class="p-3  w-1/6 "><?= date('M d, Y', strtotime($expense['date'])) ?></td>
                            <td class="p-3 w-2/6 "><?= htmlspecialchars($expense['description']) ?></td>
                            <td class="p-3 w-1/6 "><?= htmlspecialchars($expense['category_name']) ?></td>
                            <td class="p-3 w-1/6  text-right font-semibold text-red-500">-<?= number_format($expense['amount'], 2) ?></td>
                            <td class="p-3 w-1/6 text-center">
                                <button 
                                    onclick='editExpense({
                                        id:<?= (int)$expense["id"] ?>, 
                                        date: <?= json_encode($expense["date"]) ?>, 
                                        categoryId: <?= (int)($expense["category_id"] ?? 0) ?>, 
                                        description: <?= json_encode($expense["description"]) ?>, 
                                        amount:<?= (float)$expense["amount"] ?>
                                    })' 
                                    class="text-emerald-600 text-center inline-block mr-2"
                                    title="update"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <button 
                                    onclick='deleteExpense(<?= (int)$expense["id"] ?>, <?= json_encode($expense["description"]) ?>)'
                                    title="delete" 
                                    type="button" 
                                    class="text-red-600 text-center"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <!-- List of Expenses for SM screens -->
        <div class="md:hidden space-y-3">
            <?php foreach($expenses as $expense) : ?>
                <div class="relative group border border-gray-200 rounded-xl p-4 bg-white hover:shadow-lg transition-all duration-200 hover:border-gray-300">
                
                        <div class="min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    <?= date('M d, Y', strtotime($expense['date'])) ?>
                                </span>
                                <span class="font-bold text-red-500 bg-red-50 px-3 py-1 rounded-full text-sm">
                                    -<?= number_format($expense['amount'], 2) ?> Php
                                </span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-800 text-lg truncate" title="<?= htmlspecialchars($expense['description']) ?>">
                                <?= htmlspecialchars($expense['description']) ?>
                            </h3>
                            
                            <div class="mt-1 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                                    <i class="fa-regular fa-folder-open text-xs"></i>
                                    <?= htmlspecialchars($expense['category_name']) ?>
                                </span>
                            </div>
                        </div>
                    
                    <div class="flex justify-end gap-3 mt-3 ">
                        <button 
                            onclick='editExpense({
                                id:<?= (int)$expense["id"] ?>,
                                categoryId:<?= (int)($expense["category_id"] ?? 0) ?>,
                                date:<?= json_encode($expense["date"]) ?>,
                                description:<?= json_encode($expense["description"]) ?>,
                                amount:<?= (float)$expense["amount"] ?>
                            })'
                            class="flex-1 bg-emerald-50 text-emerald-600 py-2 rounded-lg text-sm font-medium"
                        >
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                        </button>
                        
                        <button 
                            onclick='deleteExpense(<?= (int)$expense["id"] ?>, <?= json_encode($expense["description"]) ?>)'
                            class="flex-1 bg-red-50 text-red-600 py-2 rounded-lg text-sm font-medium"
                        >
                            <i class="fa-solid fa-trash mr-1"></i> Delete
                        </button>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="px-3 justify-between flex mt-4 h-fit ">
            <div>
                Showing <?= min($totalRows, (($page * 10) - 9))?> 
                to <?= min($page * 10, $totalRows)?>
                of <?= $totalRows?> results
            </div>

            <div class="flex gap-2">
                <?php if($page > 1): ?>
                    <a href="?page=<?= (int)($page - 1) ?>" class="bg-white border border-blue-500 text-blue-700 px-3 py-1 rounded-md hover:bg-blue-50 flex items-center justify-center gap-3 w-fit">
                        Previous
                    </a>
                <?php else: ?>
                    <span class="cursor-not-allowed bg-gray-100 text-gray-400 px-3 py-1 rounded-md flex items-center justify-center gap-3 w-fit">
                        Previous
                    </span>
                <?php endif; ?>                
            
                <?php if($page < $totalPages): ?>
                    <a href="?page=<?= (int)($page + 1) ?>" class="bg-white border border-blue-500 text-blue-700 px-3 py-1 rounded-md hover:bg-blue-50 flex items-center justify-center gap-3 w-fit">
                        Next
                    </a>
                <?php else: ?>
                    <span class="cursor-not-allowed bg-gray-100 text-gray-400 px-3 py-1 rounded-md flex items-center justify-center gap-3 w-fit">
                        Next
                    </span>
                <?php endif; ?>
            </div>
            
        </div>
    </div>

</div>

<!-- Update Expense Modal -->
<?php
    $updateExpenseModalClass = !empty($_SESSION['update_expense_errors']) ? '' : 'hidden';
?>
<div id="update-expense-modal" class="z-50 h-full w-full overflow-hidden fixed top-0 bg-black bg-opacity-50 flex justify-center <?= $updateExpenseModalClass ?>">
    <div class="overflow-auto grid place-items-center size-full p-2">
        <div class="bg-white rounded-xl w-96 p-6 relative">
            <h2 class="text-xl font-bold mb-4">Update Expense</h2>
            <form action="update_expense" method="POST" class="">
                <input type="hidden" name="id" id="update-expense-id" value="<?= (int)($_SESSION['expense_form_data']['id'] ?? 0) ?>">
                <input type="hidden" name="redirect" value="all_expenses">
                <div>
                    <label class="block text-gray-700">Date</label>
                    <input type="date" name="date" id="update-expense-date" class="w-full border p-2 rounded" value="<?= htmlspecialchars($_SESSION['expense_form_data']['date'] ?? '') ?>">
                    <p class="text-sm text-red-500 h-5"><?= $_SESSION['update_expense_errors']['date'] ?? '' ?></p>
                </div>
                <div class="mb-5">
                    <label class="block text-gray-700">Category</label>
                    <select name="category_id" id="update-expense-category-id" class="bg-gray-100 border p-2 rounded focus:bg-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500 transition ease-in-out duration-150 w-full">
                        <option value="0">None</option>
                        
                        <?php foreach($categories as $category): ?>
                            <option value="<?= (int)$category['id'] ?>" <?= (($_SESSION['expense_form_data']['category_id'] ?? 0) == $category['id']) ? 'selected' : ''?>>
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">Description</label>
                    <input type="text" name="description" id="update-expense-description" class="w-full border p-2 rounded" value="<?= htmlspecialchars($_SESSION['expense_form_data']['description'] ?? '') ?>">
                    <p class="text-sm text-red-500 h-5"><?= $_SESSION['update_expense_errors']['description'] ?? '' ?></p>
                </div>
                <div>
                    <label class="block text-gray-700">Amount</label>
                    <input type="number" step="0.01" name="amount" id="update-expense-amount" class="w-full border p-2 rounded" value="<?= (float)($_SESSION['expense_form_data']['amount'] ?? 0) ?>">
                    <p class="text-sm text-red-500 h-5"><?= $_SESSION['update_expense_errors']['amount'] ?? '' ?></p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('update-expense-modal')" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    unset($_SESSION['update_expense_errors'], $_SESSION['expense_form_data']);
?>

<!-- Delete confirmation -->
<div id="delete-expense-modal" class="z-50 h-full w-full overflow-hidden fixed top-0 bg-black bg-opacity-50 flex justify-center hidden">
    <div class="overflow-auto grid place-items-center size-full p-2">
        <div class="bg-white rounded-xl w-96 p-6 relative">
        <h2 class="text-xl font-bold mb-4">Confirm deletion</h2>
        <form action="delete_expense" method="POST" class="space-y-4">
            <input type="hidden" name="id" id="delete-expense-id">
            <input type="hidden" name="redirect" value="all_expenses">

            <div>
                <p>Are you sure you want to delete "<span id="delete-expense-desc" class="font-medium"></span>"?</p>
            </div>
    
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('delete-expense-modal')" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                    Delete
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<dialog id="logout-modal" class="w-full max-w-sm rounded-2xl p-0 shadow-2xl backdrop:bg-black/50 backdrop:backdrop-blur-sm">
    <div class="p-6">
        <div class="mb-5 flex flex-col items-center gap-2">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <!-- Logout icon -->
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>

            <div class="text-center">
                <h2 class="text-lg font-semibold text-gray-900">
                    Confirm logout
                </h2>

                <p class="mt-1 text-sm leading-5 text-gray-500">
                    Are you sure you want to log out of your account?
                </p>
            </div>
        </div>

        <div class="flex flex-row justify-end gap-3">
            <button
                command="close"
                commandfor="logout-modal"
                type="button"
                class="rounded-sm w-1/2 bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300"
            >
                Cancel
            </button>

            <form action="logout" method="POST" class="w-1/2 ">
                <button
                    type="submit"
                    class="rounded-sm w-full bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>
</dialog>

<script src="<?= $appUrl ?>/js/app.js"></script>

</body>
</html>
