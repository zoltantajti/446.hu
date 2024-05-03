<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="title"><?=$user['callsign']?></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-header text-center"><b>Személyes adatok</b></div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="fa-duotone fa-hat-cowboy"></i> <b><?=$this->User->getPermById($user['perm'])?></b></li>
                    <li class="list-group-item"><i class="fa-solid fa-user"></i> <b><?=$user['opName']?></b></li>
                    <li class="list-group-item"><i class="fa-solid fa-location-dot"></i> <b>{QTH lokátor kód}</b></li>
                </ul>
            </div>
            <div class="card mb-3">
                <div class="card-header text-center"><b>Nyomkövetés</b></div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Utoljára itt járt:<br/><b><?=str_replace("-",".",$user['loginDate'])?></b></li>
                </ul>
            </div>
            <div class="card mb-3">
                <div class="card-header text-center"><b>Markerek</b></div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Térképen szerepel: <?=$this->Markers->isOnMap($user['callsign'])?></li>
                    <li class="list-group-item">Beküldött papagájok: <b><?=$this->Markers->addedParrots($user['id'])?></b></li>
                    <li class="list-group-item">Beküldött rádiók: <b><?=$this->Markers->addedRadios($user['id'])?></b></li>
                    <li class="list-group-item">Beküldött átjátszók: <b><?=$this->Markers->addedStations($user['id'])?></b></li>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-header text-center"><b>Rádiók</b></div>
                <ul class="list-group list-group-flush">
                    <?php foreach(json_decode($user['radios'],true) as $radio){ ?>
                    <li class="list-group-item"><?=$radio?></li>
                    <?php }; ?>
                </ul>
            </div>
            <div class="card mb-3">
                <div class="card-header text-center"><b>Antennák</b></div>
                <ul class="list-group list-group-flush">
                    <?php foreach(json_decode($user['antennas'],true) as $antenna){ ?>
                    <li class="list-group-item"><?=$antenna?></li>
                    <?php }; ?>
                </ul>
            </div>
            <div class="card mb-3">
                <div class="card-header text-center"><b>Látogatott frekvenciák</b></div>
                <ul class="list-group list-group-flush">
                    <?php foreach(json_decode($user['freqs'],true) as $freq){ ?>
                    <li class="list-group-item"><?=(str_starts_with($freq, "PMR") ? $freq : $freq . " MHz")?></li>
                    <?php }; ?>
                </ul>
            </div>
            <div class="card mb-3">
                <div class="card-header text-center"><b>QSO-k</b> <i><?=$this->Qso->countAllQso($user['callsign'])?></i></div>
                <ul class="list-group list-group-flush">
                    <?php foreach($this->Qso->getQSOByUser($user['callsign']) as $qso){ $direction = ($user['callsign'] == $qso['my_callsign'] ? '<i class="fa-sharp fa-solid fa-arrow-up-from-bracket"></i>' : '<i class="fa-solid fa-arrow-down-to-square"></i>'); ?>
                    <li class="list-group-item">
                        <div class="row d-flex">
                            <div class="col-6">
                                <?=$direction?> <?=$this->Qso->formatStatus($qso['status'])?>
                            </div>
                            <div class="col-6">
                                <?=($user['callsign'] == $qso['my_callsign']) ? $qso['rem_callsign'] : $qso['my_callsign']?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <?=$qso['distance']?>
                            </div>
                            <div class="col-3">
                                <?=$qso['suffix']?>
                            </div>
                            <div class="col-3">
                                <?=$qso['mode']?>
                            </div>
                        </div>
                    </li>
                    <?php }; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header text-center">Bemutatkozás</div>
                <div class="card-body"><?=$user['aboutME']?></div>
            </div>
        </div>
    </div>
</div>