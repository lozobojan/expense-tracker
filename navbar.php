<?php 

    $base_url = "http://localhost/expense_tracker";
    $user_name = $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'];
    $is_admin = $_SESSION['user']['is_admin'];

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03">
        <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?=$base_url?>/dashboard.php">ExpenseTracker</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link <?php if($currentPage == 'dashboard'): ?> active <?php endif; ?>" aria-current="page" href="<?=$base_url?>/dashboard.php">Početna</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($currentPage == 'reports'): ?> active <?php endif; ?>" aria-current="page" href="<?=$base_url?>/reports.php">Izvještaji</a>
            </li>

            <?php if($is_admin) : ?>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?=$base_url?>/expense_types/index.php">Tipovi troškova</a>
                </li>
            <?php endif; ?>

            <?php if($is_admin) : ?>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?=$base_url?>/expense_subtypes/index.php">Podtipovi troškova</a>
                </li>
            <?php endif; ?>

        </ul>
        <form class="d-flex" method="POST" action="<?=$base_url?>/users/logout.php" >
            <button class="btn btn-outline-danger" id="logout_button" type="submit" onmouseover="showLogoutMessage()" onmouseout="showUserName('<?=$user_name?>')">
                <?php echo $user_name; ?>
            </button>
        </form>
        </div>
    </div>
</nav>

