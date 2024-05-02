<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Felhasználók</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$this->Msg->print(); ?>
                <div class="input-group mb-3">
                    <input type="text" name="filter" id="filter" class="form-control" value="<?=$filter?>">
                    <button type="button" id="UserFilterButton" class="btn btn-info"><i class="fa fa-fw fa-search"></i></button>
                    <?php if($filter != null){ ?>
                    <a href="admin/users" class="btn btn-danger"><i class="fa fa-fw fa-times"></i></a>
                    <?php }; ?>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>HÍVÓJEL</th>
                            <th>OPERÁTOR</th>
                            <th>NÉV</th>
                            <th>JOGKÖR</th>
                            <th>AKTÍV?</th>
                            <th>
                                <!--<a href="admin/users/new/-1" class="btn btn-success">
                                    <i class="fa-solid fa-user-plus"></i> Új
                                </a>-->
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $key=>$item){ ?>
                        <tr>
                            <td><?=$item['id']?></td>
                            <td><?=$item['callsign']?></td>
                            <td><?=$item['opName']?></td>
                            <td><?=$item['name']?></td>
                            <td><?=$this->User->getPermById($item['perm'])?></td>
                            <td><?=($item['active'] == 1) ? "igen" : "nem"?></td>
                            <td>
                                <?php if($item['active'] == 1){ ?>    
                                <a href="admin/users/inactivate/<?=$item['id']?>"><i class="fa-solid fa-ban"></i></a>
                                <?php }else{ ?>
                                <a href="admin/users/activate/<?=$item['id']?>"><i class="fa-solid fa-check"></i></a>
                                <?php }; ?>
                                <a href="javascript:;" onClick="if(confirm('A törlés visszavonhatatlan! Valóban végre szeretnéd hajtani?')){document.location.assign('admin/users/delete/<?=$item['id']?>');};"><i class="fa-solid fa-user-times error"></i></a>
                            </td>
                        </tr>
                    <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      
</main>