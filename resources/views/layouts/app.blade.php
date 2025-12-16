<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Asset Yuasa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Fallback for x-cloak */
        [x-cloak] { display: none !important; }
        
        /* Prevent transitions on load to stop flickering */
        .preload * { transition: none !important; }

        /* Initial State Management to prevent FOUC */
        body.sidebar-closed .sidebar-main { width: 6rem !important; }
        body.sidebar-closed .logo-full { display: none !important; }
        body.sidebar-closed .logo-icon { display: block !important; }
        body.sidebar-closed .menu-link-text { display: none !important; }
        
        /* Default States (Sidebar Open) */
        .logo-icon { display: none; }
    </style>
</head>
<body class="bg-[#F8F9FC] text-slate-800 antialiased preload" 
      x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'false' ? false : true }" 
      x-init="$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val)); setTimeout(() => { document.body.classList.remove('preload'); document.body.classList.remove('sidebar-closed'); }, 100)">
      
    <script>
        // Pre-render script to set initial state immediately
        if (localStorage.getItem('sidebarOpen') === 'false') {
            document.body.classList.add('sidebar-closed');
        }
    </script>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div :class="{ 'w-72': sidebarOpen, 'w-24': !sidebarOpen }" class="w-72 sidebar-main sidebar-grad text-white flex-shrink-0 transition-all duration-300 ease-[cubic-bezier(0.25,0.8,0.25,1)] flex flex-col shadow-2xl z-30 overflow-hidden">
            <!-- Logo Section -->
            <div class="h-24 flex items-center justify-center relative border-b border-white/10 m-2 rounded-xl bg-white/5">
                 <div class="logo-full flex items-center gap-3 px-4" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-4">
                     <img src="{{ asset('storage/logo.jpeg') }}" alt="Logo" class="h-10 rounded shadow-lg">
                     <div class="leading-tight">
                         <span class="text-xl font-bold tracking-widest block">ASSET</span>
                         <span class="text-[10px] font-medium tracking-[0.2em] text-gray-400 block">MANAGEMENT</span>
                     </div>
                 </div>
                 <img src="{{ asset('storage/logo.jpeg') }}" alt="Logo Icon" class="logo-icon h-10 rounded shadow-lg absolute" x-show="!sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-50">
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                <p class="menu-link-text px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Menu</p>
                
                <a href="{{ route('dashboard') }}" class="sidebar-link group flex items-center px-4 py-3.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('dashboard') ? 'active text-white bg-white/10' : '' }}">
                    <span class="w-8 flex justify-center"><i class="fas fa-home text-lg group-hover:text-blue-400 transition-colors"></i></span>
                    <span class="menu-link-text ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Dashboard</span>
                </a>

                <div class="my-2 mx-4"></div>

                <!-- Asset Management Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('assets.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="w-full sidebar-link group flex items-center justify-between px-4 py-3.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('assets.*') ? 'text-white' : '' }}">
                        <div class="flex items-center">
                            <span class="w-8 flex justify-center"><i class="fas fa-boxes-stacked text-lg group-hover:text-blue-400 transition-colors"></i></span>
                            <span class="menu-link-text ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Asset Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="{ 'rotate-180': open }" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></i>
                    </button>
                    
                    <div x-show="open" x-collapse x-cloak class="pl-4 mt-1 space-y-1 overflow-hidden">
                        <a href="{{ route('assets.index') }}" class="sidebar-link group flex items-center px-4 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('assets.index', 'assets.show', 'assets.create', 'assets.edit') ? 'active text-white bg-white/5' : '' }}">
                            <span class="w-6 flex justify-center"><i class="fas fa-list text-sm"></i></span>
                            <span class="menu-link-text ml-3 font-medium text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Asset List</span>
                        </a>
                        @if(Auth::user()->role != 'pimpinan')
                        <a href="{{ route('assets.groups') }}" class="sidebar-link group flex items-center px-4 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('assets.groups') ? 'active text-white bg-white/5' : '' }}">
                            <span class="w-6 flex justify-center"><i class="fas fa-layer-group text-sm"></i></span>
                            <span class="menu-link-text ml-3 font-medium text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Asset Groups</span>
                        </a>
                        <a href="{{ route('assets.categories') }}" class="sidebar-link group flex items-center px-4 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('assets.categories') ? 'active text-white bg-white/5' : '' }}">
                            <span class="w-6 flex justify-center"><i class="fas fa-tags text-sm"></i></span>
                            <span class="menu-link-text ml-3 font-medium text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Categories</span>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="my-4 border-t border-white/10 mx-4"></div>

                <!-- Documents Section -->
                <p class="menu-link-text px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Documents</p>
                <a href="{{ route('reports.index') }}" class="sidebar-link group flex items-center px-4 py-3.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('reports.index') ? 'active text-white bg-white/10' : '' }}">
                    <span class="w-8 flex justify-center"><i class="fas fa-file-contract text-lg group-hover:text-yellow-400 transition-colors"></i></span>
                    <span class="menu-link-text ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Documents</span>
                </a>

                <div class="my-4 border-t border-white/10 mx-4"></div>

                <!-- Reports Section -->
                <p class="menu-link-text px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Reports</p>
                <a href="{{ route('reports.summary') }}" class="sidebar-link group flex items-center px-4 py-3.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/10 transition-all {{ request()->routeIs('reports.summary') ? 'active text-white bg-white/10' : '' }}">
                    <span class="w-8 flex justify-center"><i class="fas fa-chart-pie text-lg group-hover:text-green-400 transition-colors"></i></span>
                    <span class="menu-link-text ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">Reports</span>
                </a>
            </nav>
            

        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative bg-[#F8F9FC]">
            
            <!-- Top Header (Glassmorphism) -->
            <header class="h-20 glass-header flex items-center justify-between px-8 z-20 sticky top-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center rounded-lg bg-white border shadow-sm text-gray-500 hover:text-blue-900 hover:bg-blue-50 transition-all">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="hidden md:block text-xl font-bold text-gray-800 tracking-tight">
                        @if(request()->routeIs('dashboard')) Dashboard 
                        @elseif(request()->routeIs('assets.*')) Asset Management 
                        @elseif(request()->routeIs('reports.*')) Documents
                        @else Overview @endif
                    </h2>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Date -->
                     <div class="hidden md:block text-right border-r pr-6 border-gray-200">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Today</p>
                        <p class="text-sm font-bold text-gray-800">{{ now()->format('d M Y') }}</p>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-blue-900 transition-colors">{{ Auth::user()->name ?? 'Guest' }}</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider">{{ Auth::user()->role ?? 'User' }}</p>
                            </div>
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#0A1A32] to-[#1e3a66] flex items-center justify-center text-white font-bold text-lg shadow-lg ring-4 ring-gray-50 group-hover:ring-blue-50 transition-all">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-300" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                            
                            <div class="px-4 py-3 border-b border-gray-50 mb-1">
                                <p class="text-sm text-gray-500">Signed in as</p>
                                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email ?? '' }}</p>
                            </div>

                            <a href="#" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors">
                                <i class="fas fa-user-circle mr-2 text-gray-400"></i> My Profile
                            </a>
                            <a href="#" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors">
                                <i class="fas fa-cog mr-2 text-gray-400"></i> Settings
                            </a>
                            
                            <div class="border-t border-gray-50 my-1"></div>
                            
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors rounded-b-xl flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8 relative">
                 @yield('content')
                 
                 <!-- Footer -->
                 <footer class="mt-12 text-center text-xs text-gray-400 pb-4">
                     &copy; {{ date('Y') }} PT. Yuasa Battery Indonesia. <span class="mx-1">•</span> All Rights Reserved.
                 </footer>
            </main>
        </div>
    </div>

    <!-- Global Scripts -->
    @yield('scripts')

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            timer: 4000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
