<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ExpenseTracker | Dashboard</title>

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

    <div class="rounded-2xl  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
        
        <div class="bg-white rounded-2xl shadow p-6 flex flex-row gap-4 relative overflow-hidden">
            <div class=" flex flex-col">
                <i class="fas fa-wallet text-5xl text-blue-200"></i>

            </div>

            <div class="flex flex-col flex-1 overflow-hidden">

                <div class="flex items-center justify-between">
                    <p id="budget-date-display-mode" data-date='<?= htmlspecialchars($budget['date'] ?? '') ?>' class="text-gray-500">
                        <!-- formatted date -->
                    </p>
                </div>

                <!-- Display Mode -->
                <div id="budget-display-mode" class="flex items-center justify-start gap-2 overflow-hidden">
                    <div class="flex items-baseline gap-2 overflow-hidden">
                        <h2 class="text-3xl font-bold text-blue-800 overflow-x-auto overflow-y-hidden">
                            <?= number_format($budget['amount'] ?? 0, 2) ?>
                        </h2>
                        <span class="text-gray-500">Php</span>
                    </div>
                    <button onclick="toggleBudgetEdit()" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-edit bg-blue-100 rounded-full p-2 border border-blue-600"></i>
                    </button>
                </div>

                <!-- Edit Mode -->
                <div id="budget-edit-mode" class="hidden h-9 flex items-end">
                    <form action="update_budget" method="POST">
                        <div class="flex gap-2 items-center">
                            <div class="relative flex-1">
                                <input type="hidden" name="redirect" value="dashboard">
                                <input type="hidden" name="budget_id" class="w-full border p-2 rounded" value="<?= (int)$budget['id'] ?>" required>

                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" name="budget_amount"
                                    value="<?= (float)($budget['amount'] ?? 0) ?>" 
                                    step="0.01"
                                    class="w-full pl-8 pr-4 py-1 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" onclick="toggleBudgetEdit()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-1 rounded-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 flex flex-col">
            <p class="text-gray-500">Remaining budget</p>
            <div class="flex items-baseline gap-2 overflow-hidden">
                <h2 class="text-3xl font-bold text-blue-800 overflow-x-auto overflow-y-hidden"><?= number_format($balance ?? 0, 2) ?></h2>
                <span class="text-gray-500">Php</span>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow p-6 flex flex-col">
            <p class="text-gray-500">Total Expenses</p>
            <div class="flex items-baseline gap-2 overflow-hidden">
                <h2 class="text-3xl font-bold text-blue-800 overflow-x-auto overflow-y-hidden"><?= number_format($total ?? 0, 2) ?></h2>
                <span class="text-gray-500">Php</span>
            </div>
        </div>
    </div>

    <!-- Manage Categories -->
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between items-start">
        <div class="mb-4">
            <h2>
                <i class="fas fa-tags text-[#4a6fa5]" ></i>
                Manage Categories
            </h2>
        </div>
        
        <div class="w-full mb-6 gap-x-4 gap-y-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            <?php foreach($categories as $category): ?>

                <div class="bg-blue-100 text-blue-700 flex rounded p-2 text-sm relative justify-between">
                    <span class="ml-2">
                        <?= htmlspecialchars($category['category_name']) ?>
                    </span>

                    <div class="text-base">

                        <button 
                            onclick='editCategory({
                                id: <?= (int)$category["id"] ?>, 
                                name: <?= json_encode($category["category_name"]) ?>, 
                            })' 
                            class="text-emerald-600 text-center inline-block mr-2"
                            title="update"
                            >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button 
                            onclick='deleteCategory({
                                id: <?= (int)$category["id"] ?>, 
                                name: <?= json_encode($category["category_name"]) ?>
                            })'
                            title="delete" 
                            type="button" 
                            class="text-red-600 text-center"
                            >
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <form method="POST" action="add_category" id="category-add-form" class="add-category w-full flex gap-4">
            <input type="text" name="category_name" placeholder="New category name" class="border rounded px-2 py-1 border-gray-300 w-full">

            <button type="submit" class="w-24 py-1 px-4 bg-gradient-to-r from-blue-600 to-blue-900 rounded-md text-white">
                <i class="fas fa-plus"></i> Add
            </button>
        </form>

        <form method="POST" action="update_category" id="category-update-form" class="add-category w-full flex gap-4 hidden">
            <input type="hidden" name="category_id" id="category-id">
            <input type="text" name="category_name" id="category-name" placeholder="New category name" class="border rounded px-2 py-1 border-gray-300 w-full">

            <button type="submit" class="py-1 px-4 bg-gradient-to-r from-blue-600 to-blue-900 rounded-md text-white" onclick="addCategory()">
                Save
            </button>
            <button type="button" onclick="closeCategoryUpdateForm()" class="px-4 py-1 rounded bg-gray-200 hover:bg-gray-300">
                Cancel
            </button>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="grid md:grid-cols-2 gap-4">

        <button onclick="openModal('add-expense-modal')" class="bg-gradient-to-r from-blue-500 to-blue-900 text-white p-4 rounded-xl font-semibold hover:shadow-lg flex items-center justify-center gap-3">
            <i class="fas fa-plus"></i> Add Expense
        </button>

        <a href="all_expenses" class="bg-white border border-gray-300 text-gray-700 p-4 rounded-xl font-semibold hover:bg-gray-100 flex items-center justify-center gap-3">
            <i class="fas fa-list"></i> View All Expenses
        </a>

    </div>

    <!-- Recent Expenses -->
    <div class="bg-white rounded-2xl shadow p-6">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Recent Expenses</h3>
        </div>

        <!-- List of Expenses for LG screens -->
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-left border-collapse">
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
                                        categoryId:<?= (int)($expense["category_id"] ?? 0) ?>, 
                                        date: <?= json_encode($expense["date"]) ?>, 
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
                    
                    <div class="flex justify-end gap-3 mt-3 sm:hidden">
                        <button onclick='editExpense({
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
    </div>
</div>

<!-- Add Expense Modal-->
<?php
    $addExpenseModalClass = !empty($_SESSION['add_expense_errors']) ? '' : 'hidden';
?>
<div
    id="add-expense-modal"
    class="fixed top-0 z-40 flex h-full w-full items-center justify-center bg-black/50 p-4 backdrop-blur-sm <?= $addExpenseModalClass ?>"
>
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

        <!-- Header -->
        <div class="border-b border-gray-100 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <i class="fa-solid fa-receipt"></i>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Add Expense
                    </h2>

                    <p class="text-sm text-gray-500">
                        Record a new expense.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="add_expense" method="POST" class="px-6 py-5">

            <!-- Date -->
            <div>
                <label
                    for="expense-date"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Date
                </label>

                <input
                    id="expense-date"
                    type="date"
                    name="date"
                    value="<?= htmlspecialchars($_SESSION['expense_form_data']['date'] ?? '') ?>"
                    class="w-full rounded-lg border cursor-pointer border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

                <p class="mt-1 min-h-4 text-xs text-red-500">
                    <?= $_SESSION['add_expense_errors']['date'] ?? '' ?>
                </p>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label
                    for="expense-category"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Category
                </label>

                <div class="relative">
                    <select
                        id="expense-category"
                        name="category_id"
                        class="w-full rounded-lg appearance-none cursor-pointer border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >
                        <option value="0">None</option>

                        <?php foreach($categories as $category): ?>
                            <option
                                value="<?= (int)$category['id'] ?>"
                                <?= (($_SESSION['expense_form_data']['category_id'] ?? 0) == $category['id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label
                    for="expense-description"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Description
                </label>

                <input
                    id="expense-description"
                    type="text"
                    name="description"
                    value="<?= htmlspecialchars($_SESSION['expense_form_data']['description'] ?? '') ?>"
                    placeholder="e.g. Lunch, transportation..."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

                <p class="mt-1 min-h-4 text-xs text-red-500">
                    <?= $_SESSION['add_expense_errors']['description'] ?? '' ?>
                </p>
            </div>

            <!-- Amount -->
            <div class="mb-4">
                <label
                    for="expense-amount"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Amount
                </label>

                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">
                        ₱
                    </span>

                    <input
                        id="expense-amount"
                        type="number"
                        step="0.01"
                        name="amount"
                        value="<?= (float)($_SESSION['expense_form_data']['amount'] ?? 0) ?>"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-gray-300 py-2 pl-8 pr-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >
                </div>

                <p class="mt-1 min-h-4 text-xs text-red-500">
                    <?= $_SESSION['add_expense_errors']['amount'] ?? '' ?>
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button
                    type="button"
                    onclick="closeModal('add-expense-modal')"
                    class="w-1/2 rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="w-1/2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Add Expense
                </button>
            </div>

        </form>
    </div>
</div>
<?php
    unset($_SESSION['add_expense_errors']);
?>

<!-- Update Expense Modal-->
<?php
    $updateExpenseModalClass = !empty($_SESSION['update_expense_errors']) ? '' : 'hidden';
?>
<div id="update-expense-modal" class="fixed top-0 z-40 flex h-full w-full items-center justify-center bg-black/50 p-4 backdrop-blur-sm <?= $updateExpenseModalClass ?>">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        
        <!-- Header -->
        <div class="border-b border-gray-100 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Update Expense
                    </h2>

                    <p class="text-sm text-gray-500">
                        Update the details of this expense.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="update_expense" method="POST" class="px-6 py-5">
            
            <input 
                type="hidden" 
                name="id" 
                id="update-expense-id" 
                value="<?= (int)($_SESSION['expense_form_data']['id'] ?? 0) ?>"
            >
            
            <input 
                type="hidden" 
                name="redirect" 
                value="dashboard"
            >

            <!-- Date -->
            <div>
                <label 
                    for="update-expense-date" 
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Date
                </label>

                <input 
                    type="date" 
                    name="date" 
                    id="update-expense-date" 
                    class="w-full rounded-lg border cursor-pointer border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    value="<?= htmlspecialchars($_SESSION['expense_form_data']['date'] ?? '') ?>"
                >

                <p class="mt-1 min-h-4 text-xs text-red-500">
                    <?= $_SESSION['update_expense_errors']['date'] ?? '' ?>
                </p>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label 
                    for="update-expense-category-id" 
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Category
                </label>

                <div class="relative">
                    <select 
                        name="category_id" 
                        id="update-expense-category-id" 
                        class="w-full rounded-lg appearance-none cursor-pointer border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >
                        <option value="0">None</option>
                        
                        <?php foreach($categories as $category): ?>
                            <option 
                                value="<?= (int)$category['id'] ?>" 
                                <?= (($_SESSION['expense_form_data']['category_id'] ?? 0) == $category['id']) ? 'selected' : ''?>
                            >
                                <?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label 
                    for="update-expense-description"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Description
                </label>

                <input 
                    type="text" 
                    name="description" 
                    id="update-expense-description" 
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    placeholder="e.g. Lunch, transportation..."
                    value="<?= htmlspecialchars($_SESSION['expense_form_data']['description'] ?? '') ?>"
                >

                <p class="mt-1 min-h-4 text-xs text-red-500">
                    <?= $_SESSION['update_expense_errors']['description'] ?? '' ?>
                </p>
            </div>

            <!-- Amount -->
            <div class="mb-4">
                <label 
                    for="update-expense-amount"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Amount
                </label>

                <div class="relative">

                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">
                        ₱
                    </span>
                
                    <input 
                        type="number" 
                        step="0.01" 
                        name="amount" 
                        id="update-expense-amount" 
                        class="w-full rounded-lg border border-gray-300 py-2 pl-8 pr-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        placeholder="0.00"
                        value="<?= (float)($_SESSION['expense_form_data']['amount'] ?? 0) ?>"
                    >
                </div>

                <p class="mt-1 min-h-4 text-xs text-red-500">
                    <?= $_SESSION['update_expense_errors']['amount'] ?? '' ?>
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button 
                    type="button" 
                    onclick="closeModal('update-expense-modal')" 
                    class="w-1/2 rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                    Cancel
                </button>

                <button 
                    type="submit" 
                    class="w-1/2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
<?php
    unset($_SESSION['update_expense_errors'], $_SESSION['expense_form_data']);
?>

<!-- Delete confirmation for expense -->
<div id="delete-expense-modal" class="fixed top-0 z-40 flex h-full w-full items-center justify-center bg-black/50 p-4 backdrop-blur-sm hidden">
    <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl">

        <div class="p-6">
            
            <!-- Header -->
            <div class="mb-5 flex flex-col items-center gap-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <i class="fa-solid fa-trash"></i>
                </div>

                <div class="text-center">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Delete expense?
                    </h2>

                    <p class="mt-1 text-sm leading-5 text-gray-500">
                        Are you sure you want to delete
                        "<span id="delete-expense-desc" class="font-medium text-gray-700"></span>"?
                    </p>
                </div>
            </div>
            
            <!-- Form -->
            <form action="delete_expense" method="POST">
                <input type="hidden" name="id" id="delete-expense-id">
                <input type="hidden" name="redirect" value="dashboard">
        
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('delete-expense-modal')" class="w-1/2 rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="w-1/2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Delete
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Delete confirmation for category -->
<div id="delete-category-modal" class="fixed top-0 z-40 flex h-full w-full items-center justify-center bg-black/50 p-4 backdrop-blur-sm hidden">
    <div class="w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl">
        
        <div class="p-6">
            <!-- Header -->
            <div class="mb-5 flex flex-col items-center gap-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <i class="fa-solid fa-trash"></i>
                </div>

                <div class="text-center">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Delete category?
                    </h2>

                    <p class="mt-1 text-sm leading-5 text-gray-500">
                        Are you sure you want to delete
                        "<span id="delete-category-name" class="font-medium text-gray-700"></span>"?
                    </p>
                </div>
            </div>

            <!-- Form -->
            <form action="delete_category" method="POST">
                <input type="hidden" name="category_id" id="delete-category-id">
                <input type="hidden" name="redirect" value="dashboard">
        
                <!-- Actions -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('delete-category-modal')" class="w-1/2 rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="w-1/2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Logout Modal -->
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
                class="w-1/2 rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300"
            >
                Cancel
            </button>

            <form action="logout" method="POST" class="w-1/2 ">
                <button
                    type="submit"
                    class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
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
