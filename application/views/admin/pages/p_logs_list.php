<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Webhely napló</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=($filtered) ? '<a href="admin/logs" class="btn btn-info">Vissza</a>' : ''?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>IP cím</th>
                            <th>Dátum</th>
                            <th>Típus</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $item) { ?>
                        <tr>
                            <td><a href="admin/logs/ip/<?=$item['ip']?>"><?=$item['ip']?></a></td>
                            <td><?=$item['date']?></td>
                            <td><a href="admin/logs/logType/<?=$item['logType']?>"><?=$item['logType']?></a></td>
                            <td><a href="admin/logs/id/<?=$item['id']?>"><i class="fa-solid fa-folder-open"></i></a></td>
                        </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      
</main>