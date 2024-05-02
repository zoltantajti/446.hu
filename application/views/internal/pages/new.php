<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h5 class="title"><?=$event['title']?></h5>
            <div class="event-meta">
                <i class="fa fa-fw fa-calendar-alt"></i> <?=str_replace('-','.',$event['createdAt'])?>
                <i class="fa-solid fa-user ml-3"></i> Admin
            </div>
            <div class="event-details">
                <img src="<?=$this->Misc->parseImage($event['image'])?>" class="event-image" />
                <p class="event-text"><?=$event['content']?></p>
            </div>
        </div>
    </div>
</div>