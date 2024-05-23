<div class="row mb-3">
    <div class="col-12"><h4><?=$file['name']?></h4></div>
</div>
<div class="row mb-3">
    <div class="col-md-3">
        <img src="<?=$file['image']?>" class="img-responsive w-100" />
    </div>
    <div class="col-md-6 dl_meta">
        <i class="fa fa-fw fa-calendar-alt"></i> Hozzáadva <b><?=str_replace('-','.',$file['createdAt'])?></b><br/>
        <?=($file['modifiedAt'] != null) ? '<i class="fa fa-fw fa-calendar"></i> Módosítva <b>'.str_replace('-','.',$file['modifiedAt']).'</b><br/>' : ''?>
        <i class="fa fa-fw fa-download"></i> Letöltve <b><?=$file['dl_counter']?></b> alkalommal<br/>
        <i class="fa fa-fw fa-user"></i> Hozzáadta <b><?=$this->User->getNameById($file['createdUser'])?></b><br/>
    </div>
    <div class="col-md-3">
        <a href="internal/getFile/<?=$file['url']?>" target="_blank" class="btn d-block btn-success"><i class="fa fa-fw fa-download"></i> Letöltés</a>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <?=$file['description']?>
    </div>
</div>