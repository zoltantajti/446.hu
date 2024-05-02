<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Hírek</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$this->Msg->print(); ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>DÁTUM</th>
                            <th>CÍM</th>
                            <th>
                                <a href="admin/news/new/-1" class="btn btn-success">
                                    <i class="fa-solid fa-plus"></i> Új
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $key=>$item){ ?>
                        <tr>
                            <td><?=str_replace('-','.',$item['createdAt'])?></td>
                            <td><?=$item['title']?></td>
                            <td>
                                <a href="admin/news/edit/<?=$item['id']?>"><i class="fa-solid fa-pen"></i></a>&nbsp;&nbsp;
                                <a href="javascript:;" onClick="if(confirm('A törlés visszavonhatatlan! Valóban végre szeretnéd hajtani?')){document.location.assign('admin/news/delete/<?=$item['id']?>');};"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      
</main>