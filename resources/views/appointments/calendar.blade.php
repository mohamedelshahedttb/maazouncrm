@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">تقويم المواعيد</h1>
            <p class="text-gray-600 mt-2">عرض جميع المواعيد في تقويم منظم</p>
        </div>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('appointments.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                عرض القائمة
            </a>
            <a href="{{ route('appointments.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة موعد جديد +
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4 space-x-reverse">
                <button id="prevMonth" class="p-2 hover:bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h2 id="currentMonth" class="text-xl font-semibold text-gray-800"></h2>
                <button id="nextMonth" class="p-2 hover:bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            <div class="flex space-x-2 space-x-reverse">
                <button id="todayBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600">اليوم</button>
                <button id="monthView" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">شهري</button>
                <button id="weekView" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">أسبوعي</button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="grid grid-cols-7 gap-px bg-gray-200">
            <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-500">الأحد</div>
            <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-500">الاثنين</div>
            <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-500">الثلاثاء</div>
            <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-500">الأربعاء</div>
            <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-500">الخميس</div>
            <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-500">الجمعة</div>
            <div class="bg-gray-50 p-3 text-center text-sm font-medium text-gray-500">السبت</div>
        </div>
        
        <div id="calendarGrid" class="grid grid-cols-7 gap-px bg-gray-200">
            <!-- Calendar days will be populated by JavaScript -->
        </div>
    </div>
</div>

<script>
class Calendar {
    constructor() {
        this.currentDate = new Date();
        this.appointments = @json($appointments ?? []);
        this.init();
    }

    init() {
        this.renderCalendar();
        this.bindEvents();
        this.updateCurrentMonth();
    }

    renderCalendar() {
        const grid = document.getElementById('calendarGrid');
        grid.innerHTML = '';
        
        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
        const lastDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());
        
        const endDate = new Date(lastDay);
        endDate.setDate(endDate.getDate() + (6 - lastDay.getDay()));
        
        let currentDate = new Date(startDate);
        
        while (currentDate <= endDate) {
            const dayElement = this.createDayElement(currentDate);
            grid.appendChild(dayElement);
            currentDate.setDate(currentDate.getDate() + 1);
        }
    }

    createDayElement(date) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'bg-white min-h-32 p-2';
        
        const isCurrentMonth = date.getMonth() === this.currentDate.getMonth();
        const isToday = this.isToday(date);
        
        const dateHeader = document.createElement('div');
        dateHeader.className = `text-sm font-medium mb-2 ${isCurrentMonth ? 'text-gray-900' : 'text-gray-400'} ${isToday ? 'bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center' : ''}`;
        dateHeader.textContent = date.getDate();
        dayDiv.appendChild(dateHeader);
        
        const dayAppointments = this.getAppointmentsForDate(date);
        dayAppointments.forEach(appointment => {
            const appointmentElement = this.createAppointmentElement(appointment);
            dayDiv.appendChild(appointmentElement);
        });
        
        return dayDiv;
    }

    createAppointmentElement(appointment) {
        const appointmentDiv = document.createElement('div');
        appointmentDiv.className = 'text-xs p-1 mb-1 rounded cursor-pointer text-white';
        
        const statusColors = {
            'scheduled': 'bg-blue-500',
            'confirmed': 'bg-green-500',
            'in_progress': 'bg-yellow-500',
            'completed': 'bg-gray-500',
            'cancelled': 'bg-red-500'
        };
        
        appointmentDiv.className += ` ${statusColors[appointment.status] || 'bg-gray-500'}`;
        appointmentDiv.textContent = `${appointment.client?.name || 'غير محدد'} - ${appointment.service?.name || 'غير محدد'}`;
        
        appointmentDiv.addEventListener('click', () => this.showAppointmentModal(appointment));
        
        return appointmentDiv;
    }

    getAppointmentsForDate(date) {
        return this.appointments.filter(appointment => {
            const appointmentDate = new Date(appointment.appointment_date);
            return appointmentDate.toDateString() === date.toDateString();
        });
    }

    isToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }

    updateCurrentMonth() {
        const monthNames = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];
        
        document.getElementById('currentMonth').textContent = 
            `${monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
    }

    bindEvents() {
        document.getElementById('prevMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.renderCalendar();
            this.updateCurrentMonth();
        });
        
        document.getElementById('nextMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.renderCalendar();
            this.updateCurrentMonth();
        });
        
        document.getElementById('todayBtn').addEventListener('click', () => {
            this.currentDate = new Date();
            this.renderCalendar();
            this.updateCurrentMonth();
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Calendar();
});
</script>
@endsection
