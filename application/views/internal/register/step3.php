<center class="mb-3"><b>Rádió adatok</b></center>
<div class="alert alert-warning">
    Figyelem!<br/>
    Kérlek, a megadott Rádió/Antenna/Frekvencia után nyomj egy ENTER-t, és akkor fog rögzülni. Köszönöm!
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="form-placeholder-box"><i class="fa fa-fw fa-walkie-talkie"></i></span>
    <div class="form-floating" id="radiosFrameBefore">
        <textarea class="form-control" name="radios_input" id="radios_input" placeholder="Rádió hozzáadása"></textarea>
        <label for="radios_input">Rádió hozzáadása</label>
    </div>
    <input type="hidden" name="radios" id="radios" value=''/>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="form-placeholder-box-ant"><i class="fa-solid fa-tower-broadcast"></i></span>
    <div class="form-floating" id="antennaFrameBefore">
        <textarea class="form-control" name="antenna_input" id="antenna_input" placeholder="Antenna hozzáadása"></textarea>
        <label for="antenna_input">Antenna hozzáadása</label>
    </div>
    <input type="hidden" name="antennas" id="antennas" value=''/>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="form-placeholder-box-freq"><i class="fa-solid fa-signal"></i></span>
    <div class="form-floating" id="freqFrameBefore">
        <textarea class="form-control" name="freq_input" id="freq_input" placeholder="Figyelt frekvenciák (max 3)"></textarea>
        <label for="freq_input">Figyelt frekvenciák (Max 3)</label>
    </div>
    <input type="hidden" name="freqs" id="freqs" value=''/>
</div>
<div class="row">
    <div class="col-md-6">
        <a href="internal/register/2" class="btn btn-block text-uppercase button">
            Előző
    </a>
    </div>
    <div class="col-md-6 text-right">
        <button type="submit" id="nextButton" class="btn btn-block text-uppercase">
            Következő
        </button>
    </div>
</div>

<?php print_r($this->Sess->getChain()); ?>