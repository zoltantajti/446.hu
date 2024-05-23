<div class="container dl_container">
    <aside class="dl_aside">
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Keresés" value="<?=@$_GET['search']?>"/>
                <button type="submit" class="btn btn-outline-light"><i class="fa fa-fw fa-search"></i></button>
            </div>
        </form>
    </aside>
    <main class="dl_main">
        <?=$this->Downloads->breadcrumb()?>
        <?php $this->load->view($thm . 'pages/dl_' . $segment); ?>
    </main>
</div>