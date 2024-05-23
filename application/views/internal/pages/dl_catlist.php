<?php if(!empty($childrens)){ ?>
<div class="row mb-3">
    <div class="col-12"><h4>Kategóriák</h4></div>
        <?php foreach($childrens as $cat){ ?>
        <div class="col-md-3 card">
            <img src="<?=$cat['image']?>" class="card-img-top" alt="<?=$cat['name']?>" />
            <div class="card-body">
                <h5 class="card-title text-center"><?=$cat['name']?></h5>
                <?php if($cat['parent'] == 0) { ?>
                <a href="internal/downloads/<?=$cat['url']?>" class="btn d-block btn-outline-light stretched-link"><i class="fa fa-fw fa-folder-open"></i> Megnyitás</a>
                <?php }else{ ?>
                <a href="internal/downloads/<?=$current['url']?>/<?=$cat['url']?>" class="btn d-block btn-outline-light stretched-link"><i class="fa fa-fw fa-folder-open"></i> Megnyitás</a>
                <?php }; ?>
            </div>
        </div>
        <?php }; ?>
    </div>
<?php }; ?>
<?php if(!empty($files)){ ?>
    <div class="row">
        <div class="col-12"><h4>Fájlok</h4></div>
        <?php foreach($files as $file){ ?>
        <div class="col-md-3 card">
            <img src="<?=$file['image']?>" class="card-img-top" alt="<?=$file['name']?>" />
            <div class="card-body">
                <h5 class="card-title text-center"><?=$file['name']?></h5>
                <?php if($level == 1){ ?>
                <a href="internal/downloads/<?=$current['url']?>/<?=$file['url']?>" class="btn d-block btn-outline-light stretched-link"><i class="fa fa-fw fa-folder-open"></i> Megnyitás</a>
                <?php }else{ ?>
                <a href="internal/downloads/<?=$parent['url']?>/<?=$current['url']?>/<?=$file['url']?>" class="btn d-block btn-outline-light stretched-link"><i class="fa fa-fw fa-folder-open"></i> Megnyitás</a>
                <?php }; ?>
            </div>
        </div>
        <?php }; ?>
    </div>
<?php }; ?>