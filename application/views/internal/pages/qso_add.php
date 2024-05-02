<?php
    $myQTH = $this->Qso->getQTHByUserID($this->Sess->getChain('id', 'user'));

?>
<div class="container">
    <?php echo validation_errors(); ?>
    <?php echo $this->Msg->get(); ?>
    <?=form_open('internal/qso/add', 'class="qso-form" id="qso-form"')?>
    <input type="hidden" name="myPos" id="myPos" />
    <input type="hidden" name="remPos" id="remPos" />
    <div class="row">
        <div class="col-md-6 mb-3">
            <fieldset>
                <legend>Saját adataim</legend>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-user"></i></span>
                    <input type="text" class="form-control" name="my_callsign" value="<?=$this->Sess->getChain('callsign', 'user')?>" readonly/>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-user"></i></span>
                    <input type="text" class="form-control" name="my_opName" value="<?=$this->Sess->getChain('opName', 'user')?>" readonly/>
                </div>
                <div class="input-group">
                    <span class="input-group-text address-nth-1" id="basic-addon1"><i class="fa fa-fw fa-city"></i></span>
                    <input type="text" class="form-control" name="my_country" list="countries" id="my_country" value="<?=$myQTH['country']?>"/>
                    <input type="text" class="form-control" name="my_county"  list="counties" id="my_county" value="<?=$myQTH['county']?>"/>
                    <input type="text" class="form-control address-nth-4"  list="cities" name="my_city" id="my_city" value="<?=$myQTH['city']?>"/>
                </div>
                <div class="input-group">
                    <span class="input-group-text address-nth-5" id="basic-addon1"><i class="fa fa-fw fa-city"></i></span>
                    <input type="text" class="form-control" name="my_address" id="my_address" value="<?=$myQTH['address']?>"/>
                    <button type="button" class="btn btn-outline-secondary address-nth-7" onclick="calculateQTH('my');"><i class="fa fa-fw fa-map-marker-alt"></i></button>
                </div>
                <div class="input-group">
                    <span class="input-group-text address-nth-8" id="basic-addon1"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control address-nth-9" name="my_qth" id="my_qth" value="" placeholder="QTH lokátor kód" readonly/>
                </div>
            </fieldset>
        </div>
        <div class="col-md-6 mb-3">
            <fieldset>
                <legend>Ellenállomás adatai</legend>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-user"></i></span>
                    <input type="text" class="form-control" name="rem_callsign" value="" placeholder="Hívójele"/>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-user"></i></span>
                    <input type="text" class="form-control" name="rem_opname" value="" placeholder="Operátor név"/>
                </div>
                <div class="input-group">
                    <span class="input-group-text address-nth-1" id="basic-addon1"><i class="fa fa-fw fa-city"></i></span>
                    <input type="text" class="form-control" list="countries" name="rem_country" id="rem_country" value="" placeholder="Ország"/>
                    <input type="text" class="form-control" list="counties" name="rem_county" id="rem_county" value="" placeholder="Megye"/>
                    <input type="text" class="form-control address-nth-4" list="cities" name="rem_city" id="rem_city" value="" placeholder="Város"/>
                </div>
                <div class="input-group">
                    <span class="input-group-text address-nth-5" id="basic-addon1"><i class="fa fa-fw fa-city"></i></span>
                    <input type="text" class="form-control" name="rem_address" id="rem_address" value="" placeholder="Utca, házszám (ha van)"/>
                    <button type="button" class="btn btn-outline-secondary address-nth-7" onclick="calculateQTH('rem');"><i class="fa fa-fw fa-map-marker-alt"></i></button>
                </div>
                <div class="input-group">
                    <span class="input-group-text address-nth-8" id="basic-addon1"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control address-nth-9" name="rem_qth" id="rem_qth" value="" placeholder="QTH lokátor kódja" readonly/>
                </div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <fieldset>
                <legend>Összeköttetés adatai</legend>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-calendar"></i></span>
                            <input type="date" class="form-control" name="date" placeholder="Dátum" value="<?=date("Y-m-d")?>"/>
                            <input type="time" class="form-control" name="time" placeholder="Idő" value="<?=date("H:i:s")?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-wave-sine"></i></span>
                            <input type="text" name="freq" placeholder="Frekvencia" class="form-control"/>
                            <span class="input-group-text" id="basic-addon1">MHz</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-lock"></i></span>
                            <input type="text" list="ctcs" placeholder="CTCS" class="form-control" name="ctcs" />
                            <span class="input-group-text" id="basic-addon1">Hz</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-lock"></i></span>
                            <input type="text" list="dcs" placeholder="DCS" class="form-control" name="dcs" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Típus</span>
                            <select class="form-control" name="suffix" id="suffix">
                                <option value="/"> (/) Stabil</option>
                                <option value="/P"> (/P) Kitelepült / Gyalog állomás</option>
                                <option value="/M"> (/M) Mobil állomás</option>
                                <option value="/SM"> (/SM) Statikus mobilállomás</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Módja</span>
                            <select class="form-control" name="mode" id="mode">
                                <option value="/D"> (/D) Direkt</option>
                                <option value="/P"> (/P) Papagájon át</option>
                                <option value="/A"> (/A) Amatőr átjásztón át</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 hidden" id="parrotFrame">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Papagáj</span>
                            <input type="text" list="parrot" placeholder="Papagáj neve" class="form-control" name="parrot_name" />
                        </div>
                    </div>
                    <div class="col-md-4 hidden" id="amateurFrame">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Átjátszó</span>
                            <input type="text" list="antenna" placeholder="Átjátszó neve" class="form-control" name="parrot_name" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-thin fa-people-arrows"></i></span>
                            <input type="text" name="distance" id="distance" class="form-control" placeholder="Távolság" readonly/>
                            <button type="button" class="btn btn-outline-secondary" id="calculateDistance"><i class="fa-solid fa-ruler"></i></button>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-md-12 mb-3">
            <fieldset>
                <legend>Megjegyzés</legend>
                <textarea name="comment" id="comment" class="form-control" rows="7"></textarea>
            </fieldset>
        </div>
        <div class="col-md-12">
            <button type="submit" name="submit" value="1" class="btn btn-success">Beküldés</button>
        </div>
    </div>

    <?=form_close()?>
</div>

<datalist id="countries"><?php foreach($this->db->select('name')->from('countries')->get()->result_array() as $item){?><option value="<?=$item['name']?>"><?php }; ?></datalist>
<datalist id="counties"><?php foreach($this->db->select('name')->from('counties')->get()->result_array() as $item){?><option value="<?=$item['name']?>"><?php }; ?></datalist>
<datalist id="cities"><?php foreach($this->db->select('name')->from('cities')->get()->result_array() as $item){?><option value="<?=$item['name']?>"><?php }; ?></datalist>
<datalist id="ctcs"><option value="Nincs"><?php foreach($this->db->select('name')->from('ctcs')->get()->result_array() as $item){?><option value="<?=$item['name']?>"><?php }; ?></datalist>
<datalist id="dcs"><option value="Nincs"><?php foreach($this->db->select('name')->from('dcs')->get()->result_array() as $item){?><option value="<?=$item['name']?>"><?php }; ?></datalist>
<datalist id="parrot"><?php foreach($this->db->select('title')->from('markers')->where('type','parrot')->get()->result_array() as $item){?><option value="<?=$item['title']?>"><?php }; ?></datalist>
<datalist id="antenna"><?php foreach($this->db->select('title')->from('markers')->where('type','station')->get()->result_array() as $item){?><option value="<?=$item['title']?>"><?php }; ?></datalist>