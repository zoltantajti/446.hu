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
    
    <title>Belépés - 446.HU</title>
</head>
<body class="">
<div class="container">
    <div class="row px-3">
        <div class="col-lg-10 col-xl-9 card flex-row mx-auto px-0">
            <div class="img-left d-none d-md-flex"></div>
            <div class="card-body">
                <h4 class="title text-center mt-4">
                    Belépés
                </h4>
                <form class="form-box px-3" method="POST" action="" autocomplete="off">
                    <?=$this->Msg->print()?>
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                    <div class="form-input">
                        <span><i class="fa fa-user"></i></span>
                        <input type="text" name="username" id="username" placeholder="Felhasználónév vagy Hívójel" tabindex="10">
                        <?=form_error('username','<div class="error">','</div>')?>
                    </div>
                    <div class="form-input">
                        <span><i class="fa fa-key"></i></span>
                        <input type="password" name="password" id="password" placeholder="Jelszó">
                        <?=form_error('password','<div class="error">','</div>')?>
                    </div>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="rememberME" class="custom-control-input" id="cb1" name="">
                            <label class="custom-control-label" for="cb1">Jegyezzen meg</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-block text-uppercase">
                            BELÉPÉS
                        </button>
                    </div>
                    <div class="text-right mb-3">
                        <a href="internal/lostpassword/clear" class="forget-link">
                            Elfelejtetted a jelszavad?
                        </a>
                    </div>
                    <!--<div class="text-center mb-3">
                        vagy belépés az alábbiakkal
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <a href="#" class="btn btn-block btn-social btn-facebook">
                                <i class="fab fa-fw fa-facebook"></i> facebook
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-block btn-social btn-google">
                                <i class="fab fa-fw fa-google"></i> google
                            </a>
                        </div>
                    </div>-->
                    <hr class="my-4">
                    <div class="text-center mb-2">
                        Nincs még fiókod?
                        <a href="internal/register" class="register-link">
                            Regisztrálj itt!
                        </a>
                    </div>
                </form>
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