<center class="mb-3"><b>Belépési adatok</b></center>
<div class="form-input mb-3">
    <span><i class="fa fa-user"></i></span>
    <input type="text" name="callsign" placeholder="Hívójel, vagy NMHH hívójel" tabindex="10" value="<?=@$this->Sess->getChain("callsign","registration")?>">
    <?=form_error('callsign','<div class="error">','</div>')?>
</div>
<div class="form-input mb-3">
    <span><i class="fa fa-user"></i></span>
    <input type="text" name="opname" placeholder="Operátor név" tabindex="10" value="">
    <?=form_error('opname','<div class="error">','</div>')?>
</div>
<div class="form-input mb-3">
    <span><i class="fa fa-at"></i></span>
    <input type="email" name="email" placeholder="E-mail cím" tabindex="10" value="">
    <?=form_error('email','<div class="error">','</div>')?>
</div>
<div class="form-input mb-3">
    <span><i class="fa-solid fa-key"></i></span>
    <input type="password" name="password" placeholder="Jelszó" tabindex="10">
    <?=form_error('password','<div class="error">','</div>')?>
</div>
<div class="mb-3 text-right">
    <button type="submit" class="btn btn-block text-uppercase">
        Következő
    </button>
</div>