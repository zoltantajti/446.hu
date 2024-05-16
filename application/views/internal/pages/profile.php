<div class="container">
    <?=$this->Msg->get()?>
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="my-profile-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?=($segment == "login") ? 'active' : ''?>" id="login-credentials" data-bs-toggle="tab" data-bs-target="#login-credentials-panel" aria-controls="login-credentials" aria-selected="true">
                        <i class="fa fa-fw fa-sign-in-alt"></i> Belépési adatok
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?=($segment == "password") ? 'active' : ''?>" id="password-credentials" data-bs-toggle="tab" data-bs-target="#password-credentials-panel" aria-controls="password-credentials" aria-selected="true">
                        <i class="fa fa-fw fa-key"></i> Jelszó módosítása
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?=($segment == "personal") ? 'active' : ''?>" id="personal-infos" data-bs-toggle="tab" data-bs-target="#personal-infos-panel" aria-controls="personal-infos" aria-selected="false">
                        <i class="fa fa-fw fa-user"></i> Személyes adatok
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?=($segment == "radio") ? 'active' : ''?>" id="radio-infos" data-bs-toggle="tab" data-bs-target="#radio-infos-panel" aria-controls="radio-infos" aria-selected="false">
                        <i class="fa fa-fw fa-walkie-talkie"></i> Rádió & Antenna adatok
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?=($segment == "about") ? 'active' : ''?>" id="about-infos" data-bs-toggle="tab" data-bs-target="#about-infos-panel" aria-controls="about-infos" aria-selected="false">
                        <i class="fa fa-fw fa-info"></i> Bemutatkozás
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?=($segment == "marker") ? 'active' : ''?>" id="marker-infos" data-bs-toggle="tab" data-bs-target="#marker-infos-panel" aria-controls="marker-infos" aria-selected="false">
                        <i class="fa fa-fw fa-map-marker-alt"></i> Marker
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade <?=($segment == "l2ogin") ? 'show active' : ''?>" id="login-credentials-panel" role="tabpanel" aria-labelledby="login-credentials" tabindex="0">
                    <div class="alert alert-info">
                        <b>Figyelem!</b><br/>
                        Amennyiben ezen adatokat szeretnéd módosítani, kérlek írj e-mailt az info@tajtizoltan.hu e-mail címre arról az e-mail címről, amellyel regisztráltál, és írd meg a 
                        jelenlegi és a kívánt nevet!
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Hívójel</span>
                                <input type="text" class="form-control" aria-describedby="basic-addon1" disabled value="<?=$this->Sess->getChain("callsign","user")?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Operátor név</span>
                                <input type="text" class="form-control" aria-describedby="basic-addon1" disabled value="<?=$this->Sess->getChain("opName","user")?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Teljes név</span>
                                <input type="text" class="form-control" aria-describedby="basic-addon1" disabled value="<?=$this->Sess->getChain("name","user")?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade <?=($segment == "password") ? 'show active' : ''?>" id="password-credentials-panel" role="tabpanel" aria-labelledby="password-credentials" tabindex="0">
                    <?=form_open('internal/changePassword', array('id' => 'change-password-form'))?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Jelenlegi jelszavad</span>
                                <input tabindex="0" type="password" class="form-control" placeholder="Jelenlegi jelszavad" aria-label="Jelenlegi jelszavad" name="oldPW" aria-describedby="basic-addon1">
                                <?=form_error('oldPW')?>
                            </div>
                            <div class="input-group mb-3">
                                <button tabindex="3" type="submit" name="submit" value="1" class="btn btn-success">Jelszómódosítás</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Új jelszavad</span>
                                <input tabindex="1" type="password" class="form-control" placeholder="Új jelszavad" aria-label="Új jelszavad" name="newPW" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Új jelszavad</span>
                                <input tabindex="2" type="password" class="form-control" placeholder="Új jelszavad megerősítése" aria-label="Új jelszavad megerősítése" name="newPWRep" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <?=form_close()?>
                </div>
                <div class="tab-pane fade <?=($segment == "personal") ? 'show active' : ''?>" id="personal-infos-panel" role="tabpanel" aria-labelledby="personal-infos" tabindex="0">
                    <?=form_open('internal/updatePersonal', array('id' => 'update-personal-form'))?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-location-dot"></i></span>
                                <input type="text" list="countries" id="profile-countries" class="form-control" name="country" value="<?=$user['country']?>" />
                                <input type="text" list="counties" id="profile-counties" class="form-control" name="county" value="<?=$user['county']?>" />
                                <input type="text" list="cities" id="profile-city" class="form-control" name="city" value="<?=$user['city']?>" />
                                <input type="text" class="form-control" name="address" value="<?=$user['address']?>" />
                                <button type="submit" name="submit" value="1" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i></button>
                            </div>
                        </div>
                    <?=form_close()?>
                    </div>
                </div>
                <div class="tab-pane fade <?=($segment == "radio") ? 'show active' : ''?>" id="radio-infos-panel" role="tabpanel" aria-labelledby="radio-infos" tabindex="0">
                    <?=form_open('internal/updateRadios', array('id' => 'update-radio-form'))?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="radiosFrameAfter"><i class="fa-solid fa-walkie-talkie"></i></span>
                                <input type="text" id="radios_input" class="form-control" placeholder="Rádió hozzáadása" />
                                <a role="button" class="btn btn-success" id="addRadio"><i class="fa fa-fw fa-plus"></i></a>
                                <input type="hidden" name="radios" id="radios" value='<?=$user['radios']?>'/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="antennasFrameAfter"><i class="fa-light fa-tower-cell"></i></span>
                                <input type="text" id="antennas_input" class="form-control" placeholder="Antenna hozzáadása" />
                                <a role="button" class="btn btn-success" id="addAntenna"><i class="fa fa-fw fa-plus"></i></a>
                                <input type="hidden" name="antennas" id="antennas" value='<?=$user['antennas']?>'/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="freqsFrameAfter"><i class="fa-regular fa-wave-sine"></i></span>
                                <input type="text" id="freqs_input" class="form-control" placeholder="Frekvencia hozzáadása" />
                                <a role="button" class="btn btn-success" id="addFreq"><i class="fa fa-fw fa-plus"></i></a>
                                <input type="hidden" name="freqs" id="freqs" value='<?=$user['freqs']?>'/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" name="submit" value="1" class="btn btn-success">Mentés</button>
                        </div>
                    </div>
                    <?=form_close()?>
                    
                </div>
                <div class="tab-pane fade <?=($segment == "about") ? 'show active' : ''?>" id="about-infos-panel" role="tabpanel" aria-labelledby="about-infos" tabindex="0">
                   <?=form_open('internal/updateAbout')?>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <textarea name="aboutME" id="aboutME" class="form-control"><?=$user['aboutME']?></textarea>
                            <script src="./assets/js/tinymce/tinymce.min.js"></script>
                            <script>
                                tinymce.init({
                                    selector: "textarea",
                                    language: 'hu_HU',
	                                menubar: false,
                                    plugins: "image link lists",
                                    toolbar: "bold italic underline | image media | link | numlist bullist",
                                    image_class_list: [{title: 'Left', value: ''},{title: 'Right', value: 'float-right'}],
                                    content_css: '../../../assets/css/bootstrap.min.css',
                                    content_css_cors: true,
                                })
                            </script>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" name="submit" value="1" class="btn btn-success">Mentés</button>
                        </div>
                    </div>
                   <?=form_close()?>
                </div>
                <div class="tab-pane fade show active <?=($segment == "marker") ? 'show active' : ''?>" id="marker-infos-panel" role="tabpanel" aria-labelledby="marker-infos" tabindex="0">
                   <?=form_open('internal/updateMarker')?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <label class="input-group-text">Látszódjon a publikus térképen</label>
                                <select class="form-select" name="allowOnPublicMap" id="marker-type">
                                    <option value="1" <?=($user['allowOnPublicMap'] == 1) ? "selected" : "" ?>>Igen</option>
                                    <option value="0" <?=($user['allowOnPublicMap'] == 0) ? "selected" : "" ?>>Nem</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <label class="input-group-text">Látszódjon a belső térképen</label>
                                <select class="form-select" name="allowOnInternalMap" id="marker-type">
                                    <option value="1" <?=($user['allowOnInternalMap'] == 1) ? "selected" : "" ?>>Igen</option>
                                    <option value="0" <?=($user['allowOnInternalMap'] == 0) ? "selected" : "" ?>>Nem</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group">
                                <label class="input-group-text">Marker típus:</label>
                                <select class="form-select" name="markerIcon" id="marker-type">
                                    <option value="mobile_radio" <?=($user['markerIcon'] == "mobile_radio") ? "selected" : "" ?>>Kézi rádió</option>
                                    <option value="desktop_radio" <?=($user['markerIcon'] == "desktop_radio") ? "selected" : "" ?>>Fix rádió</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <textarea name="markerDesc" id="markerDesc" class="form-control"><?=$user['markerDesc']?></textarea>
                            <script src="./assets/js/tinymce/tinymce.min.js"></script>
                            <script>
                                tinymce.init({
                                    selector: "textarea",
                                    language: 'hu_HU',
	                                menubar: false,
                                    plugins: "image link lists",
                                    toolbar: "bold italic underline | image media | link | numlist bullist",
                                    image_class_list: [{title: 'Left', value: ''},{title: 'Right', value: 'float-right'}],
                                    content_css: '../../../assets/css/bootstrap.min.css',
                                    content_css_cors: true,
                                })
                            </script>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" name="submit" value="1" class="btn btn-success">Mentés</button>
                        </div>
                    </div>
                   <?=form_close()?>
                </div>
            </div>
        </div>
    </div>
</div>

<datalist id="countries"><?php foreach($this->db->select('name')->from('countries')->get()->result_array() as $item){?><option value="<?=$item['name']?>"></option> <?php }; ?></datalist>
<datalist id="counties"><?php foreach($this->db->select('name')->from('counties')->get()->result_array() as $item){?><option value="<?=$item['name']?>"></option>><?php }; ?></datalist>
<datalist id="cities"></datalist>