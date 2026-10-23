<header class="bg-white shadow-sm px-4 md:px-6 py-4 flex justify-between items-center z-30 flex-shrink-0 border-b border-gray-200">
    <div class="flex items-center gap-4">
        <button id="mobileMenuBtn" class="md:hidden text-gray-600 hover:text-blue-600 text-xl transition">
            <i class="fas fa-bars">
            </i>
        </button>
        <h2 id="pageTitle" class="text-base md:text-xl font-black text-gray-800 tracking-tight truncate uppercase max-w-[200px] md:max-w-none">Dashboard</h2>
    </div>
    <div class="flex items-center gap-4 flex-shrink-0">
        <button onclick="kunciLayar()" id="btnLock" title="Kunci Layar" class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 border border-gray-200 flex items-center justify-center hover:bg-gray-800 hover:text-white transition shadow-sm">
            <i class="fas fa-lock">
            </i>
        </button>
        <button onclick="toggleDarkModeAdmin()" id="btnDarkAdmin" class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 border border-gray-200 flex items-center justify-center hover:bg-gray-200 transition shadow-sm" title="Mode Gelap">
            <i class="fas fa-moon" id="iconDarkAdmin">
            </i>
        </button>
        <button onclick="bukaProfil()" class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm" title="Profil Saya">
            <i class="fas fa-user-cog">
            </i>
        </button>
    </div>
</header>