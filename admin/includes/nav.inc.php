<div class="navbar bg-gray-800">
    <div class="navbar-start">
        <a href="index.php" class="btn btn-ghost text-warning btn-circle mx-12">
            <i class="fi fi-br-house-chimney"></i>
        </a>
    </div>
    <div class="navbar-center">
        <div class="flex items-center justify-center lg:justify-right flex-row float-right">
            <p class="text-4xl text-white ubuntu-bold">D</p>
            <p class="text-4xl mx-1 text-white ubuntu-bold">D</p>
            <div class="loader border-t-4 rounded-full border-t-red-400 bg-orange-300 animate-spin aspect-square w-12 flex justify-center items-center text-yellow-700"></div>
            <p class="mx-1 my-8 text-4xl text-white ubuntu-bold">C</p>
        </div>
    </div>
    <div class="navbar-end">
        <div class="dropdown dropdown-end mx-12">
            <div tabindex="0" role="button" class="btn placeholder">
                <div class="w-auto rounded-lg">
                    <span class="text-xs"><?php echo $_SESSION['admin_nome']?></span>
                    <!-- USER/ADMIN -->
                </div>
            </div>
            <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52 mx-8">   
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="divider h-0 mt-0 mb-1"></div>
