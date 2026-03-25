function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function editExpense(expense) {
    document.getElementById('update-expense-id').value = expense.id;
    document.getElementById('update-expense-category-id').value = expense.categoryId;
    document.getElementById('update-expense-date').value = expense.date;
    document.getElementById('update-expense-description').value = expense.description;
    document.getElementById('update-expense-amount').value = expense.amount;
    openModal('update-expense-modal');
}

function deleteExpense(itemId, itemDesc){
    document.getElementById('delete-expense-id').value = itemId;
    document.getElementById('delete-expense-desc').textContent = itemDesc;
    openModal('delete-expense-modal');
}

function editCategory(category){
    document.getElementById('category-id').value = category.id;
    document.getElementById('category-name').value = category.name;

    document.getElementById('category-add-form').classList.add('hidden');
    document.getElementById('category-update-form').classList.remove('hidden');

}

function closeCategoryUpdateForm(){
    document.getElementById('category-id').value = null;
    document.getElementById('category-name').value = null;

    document.getElementById('category-update-form').classList.add('hidden');
    document.getElementById('category-add-form').classList.remove('hidden');

}

function deleteCategory(category){
    document.getElementById('delete-category-id').value = category.id;
    document.getElementById('delete-category-name').textContent = category.name;

    openModal('delete-category-modal');

}

const flash = document.getElementById('flash');
if(flash){

    flash.classList.add('opacity-0', '-translate-y-2');

    setTimeout(() => {
        flash.classList.remove('opacity-0', '-translate-y-2');
    }, 50);

    setTimeout(() => {
        flash.classList.add('opacity-0', '-translate-y-2');
    }, 3000);

    setTimeout(
        function(){
            flash.remove();
        },
        3500
    )
}    

function formatDate(date){
    const d = new Date(date);
    return d.toLocaleString('default', { month: 'short' }) 
        + " " 
        + d.toLocaleString('default', {year: 'numeric'})
        + " budget"        ;
}

function toggleBudgetEdit(){
    if(document.getElementById('budget-display-mode').classList.contains('hidden')){
        document.getElementById('budget-display-mode').classList.remove('hidden');
        document.getElementById('budget-edit-mode').classList.add('hidden');
    }else{
        document.getElementById('budget-display-mode').classList.add('hidden');
        document.getElementById('budget-edit-mode').classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function(){
    const dateElement = document.querySelector('#budget-date-display-mode');
    
    if(dateElement){
        const date = dateElement.dataset.date;
        dateElement.textContent = formatDate(date);
    }
})