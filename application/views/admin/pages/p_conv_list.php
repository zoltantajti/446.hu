<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Csevegések</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$this->Msg->get(); ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th width="35%">Feladó</th>
                            <th>Tárgy</th>
                            <th><div style="float:right;"><a href="javascript:refreshMails();"><i class="fa-solid fa-arrows-rotate"></i></a></div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $key=>$item){ ?>
                        <tr>
                            <td><?=$this->Contact->createIcon($item['haveUnreaded'])?></td>
                            <td><?=$item['email']?></td>
                            <td><?=$item['subject']?></td>
                            <td>
                                <a href="admin/conversations/read/<?=$item['id']?>"><i class="fa-solid fa-folder-open"></i></a>
                            
                                <!--<a href="admin/emails/edit/<?=$item['id']?>"><i class="fa-solid fa-pen"></i></a>&nbsp;&nbsp;
                                <a href="javascript:;" onClick="if(confirm('A törlés visszavonhatatlan! Valóban végre szeretnéd hajtani?')){document.location.assign('admin/emails/delete/<?=$item['id']?>');};"><i class="fa-solid fa-trash"></i></a>-->
                            </td>
                        </tr>
                    <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      
</main>