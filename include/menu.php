
<aside id="aside"  class="w-64 bg-[#222d32] transition-all duration-300 transform shadow-lg flex-shrink-0">
    <div class="p-4 bg-[#367fa9] text-white font-bold text-xl flex items-center justify-center">
        Inventaris<span class="font-light">App</span>
    </div>
    
    <div class="p-4 flex items-center space-x-3 border-b border-gray-700">
        <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center text-white">
            <i class="fas fa-user"></i>
        </div>
        <div>
            <p class="text-sm font-semibold">Maimun</p>
            <p class="text-xs text-green-400"><i class="fas fa-circle text-[8px] mr-1"></i> Online</p>
        </div>
    </div>

    <nav class="mt-4 text-sm">
        <div class="px-4 py-2 text-gray-500 uppercase text-xs font-bold">Main Navigation</div>
        <a href="perawatan.php" 
            class="flex items-center px-4 py-3 
            <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' 
                ? 'bg-[#1e282c] border-l-4 border-blue-500 text-white' 
                : 'text-gray-300 hover:bg-[#1e282c]' ?>">
            
                <i class="fas fa-tachometer-alt w-6"></i>
                <span>&nbsp; DASHBOARD</span>
        </a>
        <a href="perawatan.php" 
            class="flex items-center px-4 py-3 
            <?= basename($_SERVER['PHP_SELF']) == 'inventaris.php' 
                ? 'bg-[#1e282c] border-l-4 border-blue-500 text-white' 
                : 'text-gray-300 hover:bg-[#1e282c]' ?>">
            
                <i class="fas fa-tachometer-alt w-6"></i>
                <span>&nbsp; INVENTARIS</span>
        </a>
        <a href="perawatan.php" 
            class="flex items-center px-4 py-3 
            <?= basename($_SERVER['PHP_SELF']) == 'peminjaman.php' 
                ? 'bg-[#1e282c] border-l-4 border-blue-500 text-white' 
                : 'text-gray-300 hover:bg-[#1e282c]' ?>">
            
                <i class="fas fa-tachometer-alt w-6"></i>
                <span>&nbsp; PEMINJAMAN BARANG</span>
        </a>
        <a href="perawatan.php" 
            class="flex items-center px-4 py-3 
            <?= basename($_SERVER['PHP_SELF']) == 'perawatan.php' 
                ? 'bg-[#1e282c] border-l-4 border-blue-500 text-white' 
                : 'text-gray-300 hover:bg-[#1e282c]' ?>">
            
                <i class="fas fa-tachometer-alt w-6"></i>
                <span>&nbsp; PERAWATAN</span>
        </a>
        <a href="perawatan.php" 
            class="flex items-center px-4 py-3 
            <?= basename($_SERVER['PHP_SELF']) == 'kerusakan.php' 
                ? 'bg-[#1e282c] border-l-4 border-blue-500 text-white' 
                : 'text-gray-300 hover:bg-[#1e282c]' ?>">
            
                <i class="fas fa-tachometer-alt w-6"></i>
                <span>&nbsp; KERUSAKAN</span>
        </a>
        <a href="perawatan.php" 
            class="flex items-center px-4 py-3 
            <?= basename($_SERVER['PHP_SELF']) == 'license.php' 
                ? 'bg-[#1e282c] border-l-4 border-blue-500 text-white' 
                : 'text-gray-300 hover:bg-[#1e282c]' ?>">
            
                <i class="fas fa-tachometer-alt w-6"></i>
                <span>&nbsp; LICENSE SOFTWARE</span>
        </a>
    </nav>
</aside>