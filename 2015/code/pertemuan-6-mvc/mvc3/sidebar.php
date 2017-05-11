  <div class="sidebar">
     <div class="navigasi">
        <ul class="navmenu">
          <?php foreach ($halaman as $nama => $link): ?>
          <li><a href="<?= $link ?>" class="<?php echo ($judul == $nama) ? "selected" : "";?>"><?= $nama ?></a></li>
          <?php endforeach; ?>
        </ul>
     </div>
  </div>