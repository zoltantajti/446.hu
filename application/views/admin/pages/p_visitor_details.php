<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Látogató adatai</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="admin/visitors" class="btn btn-info mb-3">Vissza</a>
                <div class="row mb-3">
                    <div class="col-md-4">
                        IP cím: <b><?=$this->Visitor->getFlag($data['geo']) . " " . $data['ipaddr']?></b>
                    </div>
                    <div class="col-md-4">
                        Látogatások száma: <b><?=$data['visits']?></b>
                    </div>
                    <div class="col-md-4">
                        Utolsó látogatás: <b><?=$data['lastVisit']?></b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <center><b>Látogatás napló:</b></center>
                        <br/>
                        <ul class="list-group">
                        <?php 
                            $visits = json_decode($data['visitsDate'],true); 
                            foreach($visits as $key=>$item){ 
                                echo("<li class='list-group-item'>" . str_replace("-",".", $item) . "</li>"); 
                            } 
                        ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <center><b>IP Adatok:</b></center>
                        <br/>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                        
                        <ul class="list-group">
                            <?php $geo = $this->Visitor->getLocation($data['geo']); if(!isset($geo['message'])){ ?>                            
                            <li class="list-group-item">Helyszín: <b><?=$geo['zipcode'] . ", " . $geo['city'] . ", " . $geo['country_name_official']?></b></li>
                            <li class="list-group-item">ISP: <b><?=$geo['isp']?></b></li>
                            <li class="list-group-item">Cég: <b><?=$geo['organization']?></b></li>
                            <li class="list-group-item">Típus: <b><?=$geo['connection_type']?></b></li>
                            <li class="list-group-item">GPS: <b><?=$geo['latitude'] . " " . $geo["longitude"]?></b></li>
                            <li class="list-group-item" id="map" style="height:230px;"></li>
                            <script>
                                const map = L.map('map').setView([<?=$geo['latitude']?>, <?=$geo['longitude']?>], 13);
                                const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 19,
                                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                }).addTo(map);
                                const marker = L.marker([<?=$geo['latitude']?>, <?=$geo['longitude']?>]).addTo(map);
                            </script>
                            <?php }else{ ?>
                                <li class="list-group-item list-group-item-danger"><b>HIBA TÖRTÉNT!!!</b><br/>Oka: <?=$geo['message']?></li>
                            <?php }; ?>
                        </ul>                        
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
      
</main>