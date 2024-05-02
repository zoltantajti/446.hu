<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <base href="<?=site_url()?>" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow" />
    <title><?=(@$title != "") ? $title . " - " : ""?> Admin felület</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" media="all"/>
    <link href="./assets/css/fa.all.min.pro.css" rel="stylesheet" media="all"/>
    <link href="./assets/css/446admin.css" rel="stylesheet" media="all"/>
    <link rel="icon" type="image/x-icon" href="./assets/images/favicon.ico" />

    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
    <?php if(!in_array("login", $this->uri->segment_array())){ 
        echo '<main class="d-flex flex-nowrap">';
    }; ?>
    <?php $this->load->view($thm . 'sidebar'); ?>
    <?php $this->load->view($thm . 'pages/p_' . $page); ?>
    <?php if(!in_array("login", $this->uri->segment_array())){ 
        echo '</main>';
    }; ?>

    <div class="modal" id="hintModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Súgó</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="hintModal-content"></div>
            </div>
        </div>
    </div>
    <div class="toast-container p-3 top-0 end-0" id="toastPlacement" data-original-class="toast-container p-3"></div>
    <script src="https://cdn.socket.io/4.7.4/socket.io.js"></script>
    <script src="./assets/js/push/node_modules/push.js/bin/push.js"></script>
    <script id="adminjs" src="./assets/js/446admin.js?token=<?=$this->User->getToken()?>"></script>
</body>
</html>