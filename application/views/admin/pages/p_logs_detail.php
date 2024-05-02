<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Webhely napló</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="admin/logs" class="btn btn-info">Vissza</a>
                <div class="row">
                    <div class="col-md-6">
                        Időpont: <b><?=$data['date']?></b>
                    </div>
                    <div class="col-md-6">
                        Típus: <b><?=$this->Logs->getTypeName($data['logType'])?></b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        IP cím: <b><?=$data['ip']?></b>
                    </div>
                    <div class="col-md-6">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        Napló tartalma:<br/>
                        <b><code><?=$data['msg']?></code></b>
                    </div>
                </div>
            </div>
        </div>
    </div>
      
</main>