<?php $pager->setSurroundCount(2) ?>

<?php $total = $pager->getTotal() ?>
<?php if ($total !== null && $total > 0) : ?>
<div class="table-footer-control">
    <span class="table-pagination-info">
        Showing <?= $pager->getPerPageStart() ?> to <?= $pager->getPerPageEnd() ?> of <?= $total ?> entries
    </span>
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0 gap-1">
            <li class="page-item <?= $pager->hasPreviousPage() ? '' : 'disabled' ?>">
                <a class="page-link border-0" href="<?= $pager->getPreviousPage() ?: '#' ?>" aria-label="Previous">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>

            <?php foreach ($pager->links() as $link) : ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link border-0" href="<?= $link['uri'] ?>">
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach ?>

            <li class="page-item <?= $pager->hasNextPage() ? '' : 'disabled' ?>">
                <a class="page-link border-0" href="<?= $pager->getNextPage() ?: '#' ?>" aria-label="Next">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>
<?php endif ?>