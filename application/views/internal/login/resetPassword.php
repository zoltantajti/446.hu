<!DOCTYPE html>
<html lang="hu" class="h-100" data-bs-theme="light">
<head>
    <base href="<?=base_url()?>" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">    
    <meta name="robots" content="noindex, nofollow" />
    <meta name="author" content="Tajti Zoltán | tajtizoltan.hu">
    <meta name="copyright" content="Minden jog fenntartva - 446.hu" />
    
    <link rel="icon" type="image/png" href="./assets/images/favicon.png" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/fa.all.min.pro.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/446internal.css" />
    
    <title>Jelszómódosítás - 446.HU</title>
</head>
<body class="">
<div class="container">
    <div class="row px-3">
        <div class="col-lg-10 col-xl-9 card flex-row mx-auto px-0">
            <div class="img-left d-none d-md-flex"></div>
            <div class="card-body">
                <h4 class="title text-center mt-4">
                    Jelszómódosítás
                </h4>
                <?php if($form){ ?>
                <form class="form-box px-3" method="POST" action="" autocomplete="off">
                    <?=$this->Msg->get()?>                    
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                    <div class="form-input">
                        <span><i class="fa fa-key"></i></span>
                        <input type="password" name="password" id="password" placeholder="Jelszó" tabindex="10">
                        <?=form_error('password','<div class="error">','</div>')?>
                    </div>
                    <div class="form-input">
                        <span><i class="fa fa-key"></i></span>
                        <input type="password" name="password_rep" id="password_rep" placeholder="Jelszó megerősítése" tabindex="10">
                        <?=form_error('password_rep','<div class="error">','</div>')?>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-block text-uppercase">
                            Jelszómódosítás
                        </button>
                    </div>                    
                    <hr class="my-4">
                    <div class="text-center mb-2">
                        Nincs még fiókod? 
                        <a href="internal/register" class="register-link"> Regisztrálj itt!</a>  
                        <a href="internal/login" class="register-link">Belépés </a>
                    </div>
                </form>
                <?php }else{ ?>
                    <div class="alert alert-danger"><?=$msg?></div>
                <?php }; ?>
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/jquery.min.js"></script>
<script>
    $(document).ready((event) => {
        $("#username").val("");
    });
</script>
</body>
</html>