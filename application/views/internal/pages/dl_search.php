<div class="row">
    <div class="col-12"><h4>Keresés eredménye</h4></div>
    <div class="col-12">
        <?php foreach($files as $file) { ?>
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?=$file['image']?>" class="img-fluid rounded-start" />
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?=$file['name']?></h5>
                        <div class="row">
                            <div class="col-md-6">                                
                                <p class="card-text text-body-secondary">
                                    <i class="fa fa-fw fa-folder"></i> <?=$this->Downloads->fetchCategoryTree($file['category'])?><br/>
                                    <i class="fa fa-fw fa-calendar-alt"></i> <?=str_replace('-','.',$file['createdAt'])?><br/>
                                    <i class="fa fa-fw fa-user"></i> <?=$this->User->getNameById($file['createdUser'])?><br/>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <a href="<?=$this->Downloads->fetchLinkTree($file['url'],$file['category'])?>" class="btn btn-success d-block"><i class="fa fa-fw fa-folder-open"></i> Megnyitás</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php }; ?>
    </div>
</div>