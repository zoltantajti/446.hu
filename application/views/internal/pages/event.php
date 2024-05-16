<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h5 class="title"><?=$event['title']?></h5>
            <div class="event-meta">
                <i class="fa fa-fw fa-calendar-alt"></i> <?=str_replace('-','.',$event['eventStart'])?>
                <i class="fa-solid fa-flag-checkered ml-3"></i> <?=($event['eventEnd'] != null) ? str_replace('-','.',$event['eventEnd']) : "-" ?>
                <?=($marker != null) ? '<a href="internal/terkep/'.$marker['lat'].':'.$marker['lon'].':13">' : ''?>
                <i class="fa-solid fa-map-marker-alt ml-3"></i> <?=($event['place'] != null) ? $event['place'] : "-" ?>
                <?=($marker != null) ? '</a>' : ''?>
            </div>
            <div class="event-details">
                <img src="<?=$this->Misc->parseImage($event['image'])?>" class="event-image" />
                <p class="event-text"><?=$event['description']?></p>
            </div>
        </div>
    </div>
</div>