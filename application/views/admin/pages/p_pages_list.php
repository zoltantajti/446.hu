<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Oldalak</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$this->Msg->get(); ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NÉV</th>
                            <th>
                                <a href="admin/pages/new/-1" class="btn btn-success">
                                    <i class="fa-solid fa-plus"></i> Új
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $key=>$item){ ?>
                        <tr>
                            <td><?=$item['id']?></td>
                            <td><?=$item['name']?></td>
                            <td>
                                <a href="admin/pages/edit/<?=$item['id']?>"><i class="fa-solid fa-pen"></i></a>&nbsp;&nbsp;
                                <a href="javascript:;" onClick="if(confirm('A törlés visszavonhatatlan! Valóban végre szeretnéd hajtani?')){document.location.assign('admin/pages/delete/<?=$item['id']?>');};"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      
</main>