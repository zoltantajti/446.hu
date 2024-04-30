<center class="mb-3"><b>Személyes adatok</b></center>
<div class="form-input">
    <span><i class="fa fa-user"></i></span>
    <input type="text" name="name" placeholder="Teljes név" tabindex="10" value="<?=@$this->Sess->getChain("name","registration/nmhh")?>"/>
    <?=form_error('name','<div class="error">','</div>')?>
</div>
<div class="form-input">
    <span><i class="fa-solid fa-globe"></i></span>
    <input type="text" name="country" id="country" list="countryDataList" placeholder="Ország (Kezd el gépelni)" value="<?=@$this->GeoCoding->getCountryLocalName($this->Sess->getChain("country","registration/nmhh"))?>" tabindex="10" />
    <?=form_error('country','<div class="error">','</div>')?>
</div>
<div class="form-input">
    <span><i class="fa-solid fa-globe"></i></span>
    <input type="text" name="county" id="county" list="" placeholder="Megye (Kezd el gépelni)" tabindex="10" value="<?=@$this->GeoCoding->countyValue()?>"/>
    <?=form_error('county','<div class="error">','</div>')?>
</div>
<div class="form-input">
    <span><i class="fa-solid fa-city"></i></span>
    <input type="text" name="city" list="" placeholder="Város" tabindex="10" value="<?=@$this->Sess->getChain("city","registration/nmhh")?>"/>
    <?=form_error('city','<div class="error">','</div>')?>
</div>
<div class="form-input">
    <span><i class="fa-solid fa-location-dot"></i></span>
    <input type="text" name="address" list="" placeholder="Cím (Utca, házszám; Nem kötelező)" value="<?=@$this->Sess->getChain("address","registration/nmhh")?>" tabindex="10" />
    <?=form_error('address','<div class="error">','</div>')?>
</div>
<div class="row">
    <div class="col-md-6">
        <a href="register/1" class="btn btn-block text-uppercase button">
            Előző
    </a>
    </div>
    <div class="col-md-6 text-right">
        <button type="submit" class="btn btn-block text-uppercase">
            Következő
        </button>
    </div>
</div>

<?php print_r($this->Sess->getChain()); ?>