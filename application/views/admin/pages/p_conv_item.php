<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Csevegések</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>Beszélgetés <b><?=$data['conv']['email']?></b> felhasználóval</p>
                <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-messages bg-body-tertiary p-3 rounded-2" tabindex="0">
                    <?php foreach($data['items'] as $item) { ?>
                    <div class="message-box <?=$item['direction']?>">
                        <?=$item['message']?>
                        <?php if($item['attachments'] != null || $item['attachments'] != ""){ ?>
                        <div class="message-atts">
                            <?php foreach(json_decode($item['attachments'],true) as $att){ ?>
                                <a href="javascript:openAttachmentModal('<?=$att?>');"><i class="fa-solid fa-file-image"></i></a>
                            <?php }; ?>
                        </div>
                        <?php }; ?>
                        <div class="message-meta">
                            <i class="fa fa-fw fa-clock"></i> <?=$this->Contact->msgETA($item['time'])?>
                        </div>
                    </div>
                    <?php }; ?>
                </div>
                <form method="POST" action="admin/conversations/reply/<?=$data['conv']['id']?>">
                    <div class="input-group mb-3">
                        <textarea required name="message" class="form-control"></textarea>
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <button type="submit" name="submit" value="1" class="btn btn-success">Küldés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>      
</main>
<div class="modal" tabindex="-1" id="attachmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body" id="attModalBody"></div>
        </div>
    </div>
</div>