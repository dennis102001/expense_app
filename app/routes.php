<?php

use core\Router;

Router::get('/',                    'ExpenseController@dashboard');
Router::get('/dashboard',           'ExpenseController@dashboard');
Router::get('/all_expenses',        'ExpenseController@showAllExpenses');
Router::post('/add_expense',        'ExpenseController@addExpense');
Router::post('/update_expense',     'ExpenseController@updateExpense');
Router::post('/delete_expense',     'ExpenseController@deleteExpense');
Router::get('/print',               'ExpenseController@print');

Router::get('/login',               'AuthController@showLogin');
Router::get('/signup',              'AuthController@showSignup');
Router::post('/login',              'AuthController@login');
Router::post('/logout',             'AuthController@logout');
Router::post('/signup',             'AuthController@signup');
Router::get('/verify_email',        'AuthController@verifyEmail');
Router::post('/resend_verification',       'AuthController@resendVerification');

Router::post('/add_category',       'CategoryController@create');
Router::post('/update_category',    'CategoryController@update');
Router::post('/delete_category',    'CategoryController@delete');

Router::get('/forgot_password',     'AuthController@showForgotPassword');
Router::post('/forgot_password',     'AuthController@forgotPassword');
Router::get('/reset_password',     'AuthController@showResetPassword');
Router::post('/reset_password',     'AuthController@resetPassword');

Router::post('/update_budget',      'BudgetController@update');

Router::run();