<main>
    <div class="subheader">
        <ul class="nav nav-fill">
            
            <?php foreach ([
                'buying'    => 'orders/overview',
                'selling'   => '',
                'account'   => 'settings/personal'
            ] as $section => $subsection): ?>

                <li class="nav-item">
                    <a 
                        href="<?= PUBLIC_ROOT . "{$Routing->template}/{$section}" . (!empty($subsection) ? '/' . $subsection : ''); ?>" 
                        class="nav-link <?php if ($Routing->section == $section) echo 'active'; ?>"
                    >
                        <?= ucfirst($section); ?>
                    </a>
                </li>

            <?php endforeach; ?>

        </ul>
    </div>