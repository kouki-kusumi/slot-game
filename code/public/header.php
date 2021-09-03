<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="index.php" class="nav-link px-2 link-secondary fs-4">Slot game</a></li>
        <?php if (isset($_SESSION['loginUser'])) : ?>
            <li>
                <p class="px-2 link-secondary fs-4">hello, <?= h($_SESSION['loginUser']['name']) ?></p>
            </li>
            <li>
                <p class="px-2 link-secondary fs-4">Your coins : <?= h($_SESSION['loginUser']['coin']) ?></p>
            </li>
        <?php endif; ?>
    </ul>

    <?php if (isset($_SESSION['loginUser'])) : ?>
        <form action="index.php" method="post" class="col-md-3 text-end">
            <button type="submit" name="logout" class="btn btn-outline-primary me-2">Logout</button>
        </form>
    <?php else : ?>
        <div class="col-md-3 text-end">
            <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
            <a href="signup.php" class="btn btn-primary">Sign-up</a>
        </div>
    <?php endif; ?>
</header>