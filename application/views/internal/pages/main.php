<div class="container">
    <div class="row">
        <div class="col-3">
            <?php $news = $this->db->select('title,alias,short,image,createdAt')->from('news')->order_by('createdAt','desc')->limit(1,0)->get()->result_array()[0]; ?>
            <div class="card">
                <div class="card-header text-center">Legfrisebb hír</div>
                <img src="<?=$this->Misc->parseImage($news['image'])?>" class="card-img-top noradius" />
                <div class="card-body text-center">
                    <b><?=$news['title']?></b><br/>
                    <small class="text-muted"><i class="fa fa-fw fa-clock"></i> <?=str_replace('-','.',$news['createdAt'])?></small><br/>
                    <hr/>
                    <?=$news['short']?>
                </div>
                <div class="card-footer text-center">
                    <a href="internal/newsDetails/<?=$news['alias']?>" class="btn btn-outline-success stretched-link">Elolvasom</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card mb-3">
                <div class="card-header text-center">Jelvényeim</div>
                <div class="card-body text-center">
                    <?php foreach($this->Badges->getMyBadges() as $badge){ ?>
                    <img src="<?=$badge['image']?>" class="badge-image" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="badge-tooltip" data-bs-title="<b><?=$badge['name']?></b><hr/><?=$badge['description']?>" data-bs-html="true"/>
                    <?php }; ?>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header text-center">Aktív kitelepülések</div>
                <ul class="card-body list-group-flush">
                    <?php foreach($this->Markers->getActiveExpatriations() as $item){ ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-6 col-12"><?=$item['title']?></div>
                            <div class="col-md-6 col-12"><?=$item['freq']?> MHz</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">CT: <?=$item['ctcss']?> Hz</div>
                            <div class="col-md-6 col-12">DC: <?=$item['dcs']?></div>
                        </div>
                        <div class="row">
                            <a href="internal/terkep/<?=$item['lat']?>:<?=$item['lon']?>:12" class="btn btn-block btn-outline-success"><i class="fa fa-fw fa-map"></i> Megtekintés a térképen</a>
                        </div>
                    </li>
                    <?php }; ?>
                </ul>
            </div>
        </div>
        
        <div class="col-5">
            <div class="card">
                <div class="card-header text-center">Eseménynaptár</div>
                <div class="card-body mb-5" id="calendar"></div>
            </div>
        </div>
    </div>
</div>