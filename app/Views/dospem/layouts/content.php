<div id="content">
    
    <div class="main p-3">
        <div class="text-center">
        <?php
    // Memastikan jika variabel $isi ada dan memiliki nilai, maka tampilkan tampilan tersebut
    if (isset($isi) && $isi) {
        echo view($isi);
    }
    ?>
        </div>
    </div>
</div>
