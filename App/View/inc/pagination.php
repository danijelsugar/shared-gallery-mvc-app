<nav aria-label="Page navigation">
    <ul class="pagination">
        <?php if ($currentPage > 1): ?>
        <li class="page-item">
            <a class="page-link" href="<?php echo $url . 'management?page=' . ($currentPage - 1)?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $numOfPages; $i++): ?>
        <li class="page-item <?php echo ($currentPage == $i) ? 'active' : '' ?>">
            <a class="page-link" href="<?php echo $url . 'management?page=' . $i ?>"><?php echo $i ?></a>
        </li>
        <?php endfor; ?>
        <?php if ($currentPage < $numOfPages): ?>
        <li class="page-item">
            <a class="page-link" href="<?php echo $url . 'management?page=' . ($currentPage + 1) ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>