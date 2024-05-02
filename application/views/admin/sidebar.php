    <div class="col-lg-2 d-flex flex-column flex-shrink-0 p-3 text-bg-dark">
        <a href="<?=site_url()?>admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4 desktop">zAdmin</span>
            <span class="fs-4 mobile">Z</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="admin/" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-home"></i>
                    <span class="nav-item-name">Kezdőlap</span>
                </a>
            </li>
            <?php if($this->User->hasPerm(2)){ ?>
            <li class="nav-item">
                <a href="admin/pages/list" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-file"></i>
                    <span class="nav-item-name">Oldalak</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="admin/events/list" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-calendar-alt"></i>
                    <span class="nav-item-name">Események</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="admin/news/list" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-newspaper"></i>
                    <span class="nav-item-name">Hírek</span>
                </a>
            </li>
            <li><hr class="nav-item"><div class="dropdown-divider"></div></li>
            <?php }; ?>
            <?php if($this->User->hasPerm(3)){ ?>
            <li class="nav-item">
                <a href="admin/emails/list" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-envelope"></i>
                    <span class="nav-item-name">E-mailek</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="admin/markers/list" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-map-marker-alt"></i>
                    <span class="nav-item-name">Markerek</span>
                </a>
            </li>
            <li><hr class="nav-item"><div class="dropdown-divider"></div></li>
            <?php }; ?>
            <?php if($this->User->hasPerm(99)){ ?>
                <li class="nav-item">
                <a href="admin/visitors" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-eye"></i>
                    <span class="nav-item-name">Látogatók</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="admin/logs" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-file"></i>
                    <span class="nav-item-name">Napló</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="admin/users" class="nav-link" aria-current="page">
                    <i class="fa-solid fa-users"></i>
                    <span class="nav-item-name">Felhasználók</span>
                </a>
            </li> 
            <?php }; ?>
        </ul>
        <hr>
        <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <span class="nav-item-name"><strong><?=$this->User->getFName()?></strong></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="admin/changePW">Jelszómódosítás</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="admin/logout">Kilépés</a></li>
        </ul>
        </div>
    </div>