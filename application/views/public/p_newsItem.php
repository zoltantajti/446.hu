<section id="newsItem" style="margin-top:75px !important;" class="mt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="title"><?=$item['title']?></h2>
                <p class="meta">
                    <i class="fa fa-fw fa-calendar"></i> <?=str_replace("-",".",$item['createdAt'])?> | 
                    <i class="fa fa-fw fa-user"></i> Admin
                <p class="text jsfy">
                    <img src="<?=$this->Misc->parseImage($item['image'])?>" class="event-image" />
                    <?=$item['content']?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 social-share-feed">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Megosztom</span> 
                    <a 
                        href="javascript:;" 
                        onClick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?=base_url()?>public/hir/<?=$item['alias']?>','FB Share','height=350,width=350,location=no,menubar=no,resize=no,status=no,titlebar=no,toolbar=no');"
                        class="btn btn-outline-primary"
                    >
                        <i class="fab fa-fw fa-2x fa-facebook-square"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>