<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Események</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$this->Msg->get(); ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>DÁTUM</th>
                            <th>NÉV</th>
                            <th>
                                <a href="admin/events/new/-1" class="btn btn-success">
                                    <i class="fa-solid fa-plus"></i> Új
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $key=>$item){ ?>
                        <tr>
                            <td><?=str_replace('-','.',$item['eventStart'])?></td>
                            <td><?=$item['title']?></td>
                            <td>
                                <a href="admin/events/edit/<?=$item['id']?>"><i class="fa-solid fa-pen"></i></a>&nbsp;&nbsp;
                                <a href="javascript:;" onClick="if(confirm('A törlés visszavonhatatlan! Valóban végre szeretnéd hajtani?')){document.location.assign('admin/events/delete/<?=$item['id']?>');};"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      
</main>