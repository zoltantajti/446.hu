<!DOCTYPE html>
<html lang="en" class="h-100" data-bs-theme="dark">
<head>
    <base href="<?=base_url()?>" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow" />
    
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/fa.all.min.pro.css" />
    <?=@$css?>
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/system.css" />

    <link rel="icon" type="image/png" href="./assets/images/favicon.png" />
    
    <script src="./assets/js/jquery.min.js"></script>
    <title>446.HU</title>
</head>
<body class="d-flex flex-column h-100">
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?=base_url()?>">446.HU</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <?=$this->Navbar->getInternal()?>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-md-0">
<?php
    $notif = ($this->Notifications->collectNotificatable() > 0) ? true : false;
?>
                        <li class="nav-item dropdown">
                        <a class="nav-link" <?=($notif) ? 'href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"' : 'href="javascript:;" title="Nincs új értesítés"'?>>
                                <i class="fa-solid fa-bell">
                                    <?php if($notif){ ?>
                                    <span class="position-absolute top-10 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                        <?=$this->Notifications->collectNotificatable()?>
                                    </span>
                                    <?php }; ?>
                                </i>
                            </a>
                            <ul class="dropdown-menu notifications-menu">
                                <?php foreach($this->Notifications->getAllNotification() as $notif){ echo($this->Notifications->showNotif($notif)); }; ?>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-fw fa-user"></i> <?=$this->Sess->getChain('callsign','user'); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if($this->Sess->getChain('perm','user') == 99){ ?>
                                <li><a class="dropdown-item" href="admin"><i class="fa-solid fa-fw fa-user-tie"></i> Admin</a></li>
                                <?php }; ?>
                                <li><a class="dropdown-item" href="internal/profile"><i class="fa fa-fw fa-user"></i> Profilom</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="internal/logout"><i class="fa fa-fw fa-sign-out-alt"></i> Kilépés</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-shrink-0">
        <?php $this->load->view($page); ?>
    </main>
    <section id="chat">
        <!--<button id="openChat"></button>-->
        <div class="popup-container" id="popupContainer">
            <div class="popup">
                <div class="popup-header">
                    Laura <i>(AI asszisztens)</i>
                    <button id="closeChat">X</button>
                </div>
                <div class="popup-body">
                    <div class="chat-messages">
                        <div class="message received">
                            Hello! How are you?
                        </div>
                        <div class="message sent">
                            Hi! I'm good, thanks for asking.
                        </div>
                    </div>
                    <div class="chat-input">
                        <textarea placeholder="Type your message..."></textarea>
                        <button>Send</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer mt-auto py-3 bg-body-tertiary">
        <div class="container">
            <span class="text-body-secondary">Minden jog fenntartva &copy; <a href="https://446.hu">446.hu</a></span>
        </div>
    </footer>    
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/openai.gpt.js"></script>
    <script src="./assets/js/openai.js"></script>
    <script src="./assets/js/446script.js"></script>
    <?=@$js?>
</body>
</html>