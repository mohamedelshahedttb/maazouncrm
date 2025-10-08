<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\PartnerAssistanceRequestController;
use App\Http\Controllers\SupplierOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientSourceController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\GovernorateController;
use Illuminate\Http\Request;
use App\Services\PricingService;
use App\Models\Area;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Areas CRUD
    Route::resource('areas', AreaController::class)->except(['show']);

    // Governorates CRUD
    Route::resource('governorates', GovernorateController::class)->except(['show']);

    // Pricing calculation endpoint
    Route::post('/pricing/calculate', function (Request $request, PricingService $pricing) {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id', // optional when governorate is present
            'area_id' => 'nullable|exists:areas,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'mahr' => 'nullable|numeric|min:0',
        ]);
        $price = $pricing->calculate(
            (int) $validated['service_id'],
            isset($validated['area_id']) ? (int)$validated['area_id'] : null,
            isset($validated['mahr']) ? (float)$validated['mahr'] : null,
            isset($validated['governorate_id']) ? (int)$validated['governorate_id'] : null
        );
        return response()->json(['price' => $price]);
    })->name('pricing.calculate');

    // Clients Management
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/print', [ClientController::class, 'print'])->name('clients.print');
    Route::get('clients/{client}/conversations', [ClientController::class, 'conversations'])->name('clients.conversations');
    Route::get('clients/{client}/orders', [ClientController::class, 'orders'])->name('clients.orders');
    Route::get('clients-kanban', [ClientController::class, 'kanban'])->name('clients.kanban');
    Route::post('clients/{client}/documents', [ClientController::class, 'storeDocuments'])->name('clients.documents.store');
    Route::delete('clients/{client}/documents/{document}', [ClientController::class, 'destroyDocument'])->name('clients.documents.destroy');
    Route::get('clients/{client}/appointments/create', [ClientController::class, 'createAppointment'])->name('clients.appointments.create');
    Route::post('clients/{client}/appointments', [ClientController::class, 'storeAppointment'])->name('clients.appointments.store');
    Route::post('clients/{client}/transfer-to-operations', [ClientController::class, 'transferToOperations'])->name('clients.transfer-to-operations');

    // Custom media serving route to avoid permission issues
    Route::get('media/{media}/serve', function (\Spatie\MediaLibrary\MediaCollections\Models\Media $media) {
        $path = storage_path('app/public/' . $media->getPath());
        
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }
        
        $mimeType = mime_content_type($path);
        $fileName = $media->file_name;
        
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    })->name('media.serve');

    Route::get('media/{media}/download', function (\Spatie\MediaLibrary\MediaCollections\Models\Media $media) {
        $path = storage_path('app/public/' . $media->getPath());
        
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }
        
        $mimeType = mime_content_type($path);
        $fileName = $media->file_name;
        
        return response()->download($path, $fileName, [
            'Content-Type' => $mimeType
        ]);
    })->name('media.download');

    // Users Management
Route::resource('users', UserController::class);
Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::post('users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

    // Services
    Route::resource('services', ServiceController::class);

    // Client Sources
    Route::resource('client-sources', ClientSourceController::class);

    // Appointments & Scheduling
    Route::get('appointments/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
    Route::resource('appointments', AppointmentController::class);
    Route::post('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Partners
    Route::resource('partners', PartnerController::class);
    Route::resource('partner-assistance-requests', PartnerAssistanceRequestController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);
    Route::resource('supplier-orders', SupplierOrderController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::get('products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');

    // Operations & Execution
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/calendar', [TaskController::class, 'calendar'])->name('tasks.calendar');
    Route::post('tasks/{task}/start', [TaskController::class, 'start'])->name('tasks.start');
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

    // Client Orders
    Route::resource('client-orders', ClientOrderController::class);
    Route::post('client-orders/{order}/confirm', [ClientOrderController::class, 'confirm'])->name('client-orders.confirm');

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-as-paid');
    Route::post('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');

    // Conversations
    Route::resource('conversations', ConversationController::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('clients', [ReportController::class, 'clients'])->name('clients');
        Route::get('products', [ReportController::class, 'products'])->name('products');
        Route::get('operations', [ReportController::class, 'operations'])->name('operations');
        Route::get('performance', [ReportController::class, 'performance'])->name('performance');
        Route::get('revenue', [ReportController::class, 'revenue'])->name('revenue');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Integrations
    Route::prefix('integrations')->name('integrations.')->group(function () {
        Route::get('/', [IntegrationController::class, 'index'])->name('index');
        Route::get('whatsapp/create', [IntegrationController::class, 'createWhatsApp'])->name('whatsapp.create');
        Route::post('whatsapp', [IntegrationController::class, 'storeWhatsApp'])->name('whatsapp.store');
        Route::get('whatsapp/{whatsapp}/edit', [IntegrationController::class, 'editWhatsApp'])->name('whatsapp.edit');
        Route::put('whatsapp/{whatsapp}', [IntegrationController::class, 'updateWhatsApp'])->name('whatsapp.update');
        Route::get('facebook/create', [IntegrationController::class, 'createFacebook'])->name('facebook.create');
        Route::post('facebook', [IntegrationController::class, 'storeFacebook'])->name('facebook.store');
        Route::get('facebook/{facebook}/edit', [IntegrationController::class, 'editFacebook'])->name('facebook.edit');
        Route::put('facebook/{facebook}', [IntegrationController::class, 'updateFacebook'])->name('facebook.update');
        Route::delete('{type}/{id}', [IntegrationController::class, 'destroy'])->name('destroy');
        Route::post('{type}/{id}/test', [IntegrationController::class, 'testWhatsApp'])->name('test');
        Route::post('{type}/{id}/toggle-status', [IntegrationController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('{type}/{id}/templates', [IntegrationController::class, 'messageTemplates'])->name('templates');
        Route::put('{type}/{id}/templates', [IntegrationController::class, 'updateTemplates'])->name('update-templates');
        Route::post('send-daily-notifications', [IntegrationController::class, 'sendDailyNotifications'])->name('send-daily');
    });

    // Webhooks (public routes)
    Route::prefix('webhooks')->name('webhooks.')->group(function () {
        Route::get('whatsapp/verify', [WebhookController::class, 'whatsappVerify'])->name('whatsapp.verify');
        Route::post('whatsapp', [WebhookController::class, 'whatsappWebhook'])->name('whatsapp');
        Route::get('facebook/verify', [WebhookController::class, 'facebookVerify'])->name('facebook.verify');
        Route::post('facebook', [WebhookController::class, 'facebookWebhook'])->name('facebook');
    });
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Simple test route
Route::get('test/hello', function() {
    return 'Hello World!';
});

// Simple file serving routes
Route::get('files/{id}/{filename}', function ($id, $filename) {
    $path = storage_path('app/public/' . $id . '/' . $filename);
    
    if (!file_exists($path)) {
        abort(404, 'File not found: ' . $path);
    }
    
    $mimeType = mime_content_type($path);
    
    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $filename . '"'
    ]);
})->name('files.serve');

Route::get('files/{id}/{filename}/download', function ($id, $filename) {
    $path = storage_path('app/public/' . $id . '/' . $filename);
    
    if (!file_exists($path)) {
        abort(404, 'File not found: ' . $path);
    }
    
    $mimeType = mime_content_type($path);
    
    return response()->download($path, $filename, [
        'Content-Type' => $mimeType
    ]);
})->name('files.download');

require __DIR__.'/auth.php';
