<!DOCTYPE html>
<html>
<head>
    <title>ExpenseTracker | Print Expenses Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        @media print {

            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?=  $appUrl ?>/css/app.css">
    <link rel="icon" type="image/svg+xml" href="<?= $appUrl ?>/den-icon.svg">
</head>

<body onload="window.print()" class="bg-gray-100 print:bg-white">

    <div class="print:hidden p-4">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded">Print</button>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-8 shadow-md print:shadow-none print:max-w-none print:p-0">
        <h2 class="text-2xl font-bold mb-6 text-center">Expenses Report</h2>

        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100  text-gray-600 ">
                    <th class="p-3  w-2/6 ">Date</th>
                    <th class="p-3 w-2/6 ">Description</th>
                    <th class="p-3 w-1/6 ">Category</th>
                    <th class="p-3 w-1/6  text-right">Amount</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                <?php foreach($expenses as $expense) : ?>
                    <tr>
                        <td class="p-3  w-2/6 "><?= date('M d, Y', strtotime($expense['date'])) ?></td>
                        <td class="p-3 w-2/6 "><?= htmlspecialchars($expense['description']) ?></td>
                        <td class="p-3 w-1/6 "><?= htmlspecialchars($expense['category_name']) ?></td>
                        <td class="p-3 w-1/6  text-right font-semibold text-red-500">-<?= number_format($expense['amount'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>        
    </div>

</body>
</html>