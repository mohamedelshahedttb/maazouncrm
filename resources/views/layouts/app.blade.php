<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Maazoun CRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-blue-50">
    <div class="min-h-screen flex">
        <!-- Left Sidebar -->
        <div class="w-64 bg-blue-50 shadow-lg border-l border-blue-200 flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-blue-200 flex-shrink-0">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-blue-700">Maazoun CRM</h1>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto">
                <div class="px-6 space-y-4 py-4">
                    <!-- Dashboard -->
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3 px-3">القائمة الرئيسية</h3>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            <span class="font-medium">لوحة القيادة</span>
                        </a>
                    </div>

                    <!-- Client Management -->
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3 px-3">إدارة العملاء</h3>
                        <a href="{{ route('clients.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('clients.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('clients.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium">إدارة العملاء</span>
                        </a>

                        <a href="{{ route('services.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 mt-2 {{ request()->routeIs('services.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('services.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z"></path>
                            </svg>
                            <span class="font-medium">إدارة الخدمات</span>
                        </a>

                        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 mt-2 {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('users.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium">إدارة المستخدمين</span>
                        </a>

                        <a href="{{ route('client-sources.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 mt-2 {{ request()->routeIs('client-sources.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('client-sources.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="font-medium">مصادر العملاء</span>
                        </a>
                    </div>

                    <!-- Appointments & Scheduling -->
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3 px-3">المواعيد والجدولة</h3>
                        <a href="{{ route('appointments.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('appointments.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('appointments.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">المواعيد والجدولة</span>
                        </a>
                    </div>

                    <!-- Business Operations -->
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3 px-3">العمليات التجارية</h3>
                        <a href="{{ route('partners.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('partners.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('partners.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium">المشايخ</span>
                        </a>

                        <a href="{{ route('suppliers.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 mt-2 {{ request()->routeIs('suppliers.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('suppliers.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            <span class="font-medium">الموردون</span>
                        </a>

                        <a href="{{ route('tasks.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 mt-2 {{ request()->routeIs('tasks.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tasks.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="font-medium">العمليات والتنفيذ</span>
                        </a>
                    </div>

                    <!-- Products & Services -->
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3 px-3">المنتجات والخدمات</h3>
                        <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('products.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium">إدارة المنتجات</span>
                        </a>
                    </div>

                    <!-- Financial -->
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3 px-3">الأمور المالية</h3>
                        <a href="{{ route('invoices.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('invoices.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium">الفواتير</span>
                        </a>
                    </div>

                    <!-- Analytics -->
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3 px-3">التحليلات والتقارير</h3>
                        <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium">التقارير</span>
                        </a>
                    </div>

                    <!-- Integrations -->
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3 px-3">التكاملات</h3>
                        <a href="{{ route('integrations.index') }}" class="flex items-center px-4 py-3.5 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('integrations.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-500 shadow-sm' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('integrations.*') ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="font-medium">التكاملات</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-blue-200 flex-shrink-0 bg-blue-50">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center mr-3">
                        <span class="text-blue-700 font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-blue-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-600">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-blue-400 hover:text-red-600 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-blue-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
</body>
</html>
