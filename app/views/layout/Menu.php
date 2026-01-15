<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <!-- --------------------------
    * Icono - Mayor
    ------------------------------->
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand">
            <a href="." class="text-decoration-none">
                <img src="public/assets/img/effective.png" width="110" height="32" alt="Tabler" class="navbar-brand-image">
                Assestmen
            </a>
        </h1>
        <!-- --------------------------
        * Menu - Item
        ------------------------------->
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <!-- --------------------------
            * Usuarios
            ------------------------------->
            <ul class="navbar-nav pt-lg-3">
                <!-- --------------------------
                * Para un solo link
                ------------------------------->
                <li class="nav-item">
                    <a class="nav-link" href="./dashboard">
                        <span class="nav-link-icon">
                            <i class="fa-solid fa-users fa-fw"></i>
                        </span>
                        <span class="nav-link-title">Usuarios</span>
                    </a>
                </li>
                <!-- --------------------------
                * Para varios sub menus anidados
                ------------------------------->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                        <span class="nav-link-icon">
                            <i class="fa-solid fa-users fa-fw"></i>
                        </span>
                        <span class="nav-link-title">
                            Gestión de Usuarios
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item" href="./usuarios-lista">
                                    <i class="fa-solid fa-list fa-fw me-2"></i> Ver Lista
                                </a>
                                <a class="dropdown-item" href="./usuarios-agregar">
                                    <i class="fa-solid fa-user-plus fa-fw me-2"></i> Agregar
                                </a>
                                <a class="dropdown-item" href="./usuarios-editar">
                                    <i class="fa-solid fa-user-pen fa-fw me-2"></i> Editar
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="./usuarios-eliminar">
                                    <i class="fa-solid fa-trash fa-fw me-2"></i> Eliminar
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</aside>