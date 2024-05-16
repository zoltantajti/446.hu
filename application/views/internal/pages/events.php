<div class="container">
    <?=$pagi?>
    <div class="row">
        <?php foreach($events as $k=>$v){ ?>
        <div class="col-md-3 mb-3">
            <div class="card">
                <img src="<?=$this->Misc->parseImage($v['image'])?>" class="card-img-top card-img-fixed" />
                <div class="card-body">
                    <h5 class="card-title text-center"><?=$v['title']?></h5>
                    <p class="card-text">
                        <?=$v['shortDesc']?>
                    </p>
                    <a href="internal/event/<?=$v['seoLink']?>" class="btn d-block btn-primary">Részletek</a>
                </div>
                <div class="card-footer text-body-secondary">
                    <i class="fa fa-fw fa-calendar-alt"></i> <?=str_replace('-','.',$v['eventStart'])?></i>
                    <?=($this->Events->hasEventOnMap($v['id']) == 1) ? '<i class="fa fa-fw fa-map-marker-alt" style="float:right; margin-top:5px;"></i>' : '' ?>
                </div>
            </div>
        </div>
        <?php }; ?>
    </div>
    <?=$pagi?>
</div>