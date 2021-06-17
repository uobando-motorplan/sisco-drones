<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your request. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);
Route::get('/', 'HomeController@index')->name('home');
// Usuario
Route::get('replace-temporary-password', 'UserController@replace_temporary_password')->name('users.replace_temporary_password');
Route::put('update-temporary-password', 'UserController@update_temporary_password')->name('users.update_temporary_password');
Route::put('email', 'UserController@update_email')->name('users.update_email');
Route::put('contact-information', 'UserController@update_contact_information')->name('users.update_contact_information');
Route::put('bank-account', 'UserController@update_bank')->name('users.update_bank');
Route::put('password', 'UserController@update_password')->name('users.update_password');
Route::put('photo', 'UserController@update_photo')->name('users.update_photo');
Route::get('profile', 'UserController@profile')->name('users.profile');
// Prospecto
Route::get('referreds/search', 'CustomerController@search')->name('customers.search');
Route::post('referreds/find', 'CustomerController@find')->name('customers.find');
Route::get('referreds/create', 'CustomerController@create')->name('customers.create');
Route::post('referreds/store', 'CustomerController@store')->name('customers.store');
// Cotizaciones
Route::get('referreds', 'QuotationController@index')->name('quotations.index');
Route::get('referreds/datatables', 'QuotationController@datatables')->name('quotations.datatables');
Route::get('referreds/{quotation}', 'QuotationController@show')->name('quotations.show');
Route::get('referreds/{customer}/create', 'QuotationController@create')->name('quotations.create');
Route::post('referreds/{customer}/store', 'QuotationController@store')->name('quotations.store');
Route::get('referreds/{quotation}/edit', 'QuotationController@edit')->name('quotations.edit');
Route::put('referreds/{quotation}', 'QuotationController@update')->name('quotations.update');
Route::get('quotations/report-criteria', 'QuotationController@criteria')->name('quotations.criteria');
// Reportes
Route::get('reports/criteria', 'QuotationController@criteria')->name('quotations.criteria');
Route::get('reports', 'QuotationController@report')->name('quotations.report');
// Validaciones
Route::post('validations/validar_cedula', 'ValidationController@validar_cedula')->name('validations.validar_cedula');
Route::post('validations/validar_ruc', 'ValidationController@validar_ruc')->name('validations.validar_ruc');
// Ciudades
Route::get('cities', 'CityController@index')->name('cities.index');
// Planes
Route::get('plans', 'PlanController@index')->name('plans.index');
Route::get('plans/{product}/fee_range', 'PlanController@fee_range')->name('plans.fee_range');
// Preferencias
Route::get('preferences', 'PreferenceController@index')->name('preferences.index');
// Notificaciones
Route::post('notifications/read', 'NotificationController@readAll')->name('notifications.readAll');
Route::get('notifications/datatables', 'NotificationController@datatables')->name('notifications.datatables');
Route::delete('notifications/{id}', 'NotificationController@destroy')->name('notifications.destroy');
Route::get('notifications', 'NotificationController@index')->name('notifications.index');
// Agenda
Route::get('monitoring', 'EventController@index')->name('events.index');
// Comisiones
Route::get('accumulated-commissions', 'PaymentRequestController@accumulated')->name('payment_requests.accumulated');
Route::get('payment-requests/datatables', 'PaymentRequestController@datatables')->name('payment_requests.datatables');
Route::get('payment-requests', 'PaymentRequestController@index')->name('payment_requests.index');
Route::get('payment-requests/create', 'PaymentRequestController@create')->name('payment_requests.create');
Route::post('payment-requests', 'PaymentRequestController@store')->name('payment_requests.store');
Route::put('payment-requests/{payment_request}', 'PaymentRequestController@cancel')->name('payment_requests.cancel');
Route::get('payment-requests/{payment_request}', 'PaymentRequestController@show')->name('payment_requests.show');
// Artículos
Route::get('items/add', 'ItemController@add')->name('items.add');
Route::get('items/remove', 'ItemController@remove')->name('items.remove');
Route::get('items/list', 'ItemController@list')->name('items.list');
Route::get('items/{product}', 'ItemController@index')->name('items.index');
Route::get('items/{product}/search', 'ItemController@search')->name('items.search');
Route::get('items/{item}/gallery', 'ItemController@gallery')->name('items.gallery');
// Brochures
Route::post('brochures/{brochure}/notify', 'BrochureController@notify')->name('brochures.notify');
Route::get('brochures/{slug}/pdf', 'BrochureController@pdf')->name('brochures.pdf');
Route::get('brochures/{product}/create', 'BrochureController@create')->name('brochures.create');
Route::post('brochures/{product}/store', 'BrochureController@store')->name('brochures.store');
// Librería
Route::get('resources', 'FolderController@index')->name('folders.index');
Route::get('resources/{folder}', 'FolderController@show')->name('folders.show');
