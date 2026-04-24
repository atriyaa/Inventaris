<header class="bg-[#3c8dbc] text-white h-14 flex items-center justify-between px-4 shadow-md">
    <button id="toggle-btn"  class="hover:bg-blue-600 p-2 rounded"><i class="fas fa-bars"></i></button>
    <div class="flex items-center space-x-4 text-sm">
        <span><i class="fas fa-user-circle mr-1"></i><?php echo $_SESSION["username"]; ?></span>
        <button class="hover:underline"><i class="fas fa-sign-out-alt mr-1"></i> <a href="../index.php"> LOGOUT</a></button>
    </div>
</header>