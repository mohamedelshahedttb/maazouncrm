<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Product;
use App\Models\Task;
use App\Models\ClientOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();
        $totalAppointments = Appointment::count();
        $totalServices = Service::count();
        $totalProducts = Product::count();
        
        $monthlyClients = Client::whereMonth('created_at', now()->month)->count();
        $monthlyAppointments = Appointment::whereMonth('appointment_date', now()->month)->count();
        
        $recentClients = Client::latest()->take(5)->get();
        $upcomingAppointments = Appointment::where('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        return view('reports.index', compact(
            'totalClients',
            'totalAppointments', 
            'totalServices',
            'totalProducts',
            'monthlyClients',
            'monthlyAppointments',
            'recentClients',
            'upcomingAppointments'
        ));
    }

    public function products()
    {
        $products = Product::with('supplier')->get();
        $lowStockProducts = Product::where('stock_quantity', '<=', DB::raw('min_stock_level'))
            ->orWhere('stock_quantity', '<=', 10)
            ->get();
        
        $productCategories = Product::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        return view('reports.products', compact('products', 'lowStockProducts', 'productCategories'));
    }

    public function operations()
    {
        $dailyServices = Appointment::whereDate('appointment_date', today())->count();
        $monthlyServices = Appointment::whereMonth('appointment_date', now()->month)->count();
        
        $delayedTasks = Task::where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->get();
            
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();

        return view('reports.operations', compact(
            'dailyServices',
            'monthlyServices',
            'delayedTasks',
            'pendingTasks',
            'completedTasks'
        ));
    }

    public function performance()
    {
        $monthlyPotentialClients = Client::whereMonth('created_at', now()->month)->count();
        $confirmedClients = ClientOrder::where('status', 'confirmed')->count();
        $monthlyServices = Appointment::whereMonth('appointment_date', now()->month)->count();
        
        $topServices = Service::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(5)
            ->get();

        return view('reports.performance', compact(
            'monthlyPotentialClients',
            'confirmedClients',
            'monthlyServices',
            'topServices'
        ));
    }

    public function revenue()
    {
        $monthlyRevenue = ClientOrder::whereMonth('created_at', now()->month)
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $monthlyOrders = ClientOrder::whereMonth('created_at', now()->month)
            ->where('status', 'completed')
            ->count();
            
        $annualRevenue = ClientOrder::whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $annualOrders = ClientOrder::whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->count();
            
        $pendingPayments = ClientOrder::where('status', 'completed')
            ->whereRaw('total_amount > paid_amount OR paid_amount IS NULL')
            ->sum(DB::raw('total_amount - COALESCE(paid_amount, 0)'));

        return view('reports.revenue', compact(
            'monthlyRevenue',
            'monthlyOrders',
            'annualRevenue',
            'annualOrders',
            'pendingPayments'
        ));
    }

    public function clients()
    {
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'active')->count();
        $potentialClients = Client::where('status', 'potential')->count();
        $completedClients = Client::where('status', 'completed')->count();
        
        $monthlyNewClients = Client::whereMonth('created_at', now()->month)->count();
        $previousMonthClients = Client::whereMonth('created_at', now()->subMonth()->month)->count();
        
        $clientsBySource = Client::with('source')
            ->select('source_id', DB::raw('count(*) as count'))
            ->groupBy('source_id')
            ->get();
            
        $clientsByService = Client::with('service')
            ->select('service_id', DB::raw('count(*) as count'))
            ->groupBy('service_id')
            ->get();
            
        $recentClients = Client::with(['service', 'source'])
            ->latest()
            ->take(10)
            ->get();
            
        $topServices = Service::withCount('clients')
            ->orderBy('clients_count', 'desc')
            ->take(5)
            ->get();

        return view('reports.clients', compact(
            'totalClients',
            'activeClients',
            'potentialClients',
            'completedClients',
            'monthlyNewClients',
            'previousMonthClients',
            'clientsBySource',
            'clientsByService',
            'recentClients',
            'topServices'
        ));
    }
}
