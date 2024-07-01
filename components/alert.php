<?php if(isset($result) && $result) { ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    Aksi Berhasil
    <a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </a>
</div>
<?php }else if(isset($result) && !$result) { ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Terjadi kesalahan
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php } ?>