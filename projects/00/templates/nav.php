<body class="has-navbar-fixed top">
    <!-- BEGIN PAGE HEADER -->
    <header class="container">

        <!-- BEGIN MAIN NAV -->
        <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="index.php">
                    <span class="icon-text">
                        <span class="icon">
                           <i class="fas fa-yin-yang fa-lg"></i>
                        </span>
                        <span>&nbsp;<?= $siteName ?></span>
                    </span>
                </a>
                <a class="navbar-burger" aria-label="menu" aria-expanded="false">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="#">Home</a>
                    <a class="navbar-item" href="#">About</a>
                </div>
                <div class="nabar-end">
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="contact.php">Contact us</a>
                            <a class="button is-light">Log in</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- END MAIN NAV -->
        <section class="block">&nbsp;<!--only for spacing purposes--></section>
        <!-- BEGIN HERO -->
        <section class="hero is-primary">
         <div class="hero-body">
            <p class="title">Hero title</p>
            <p class="subtitle">Hero subtitle</p>
         </div>
</section>
        <!-- END HERO -->

    <?php if (!empty($_SESSION['messages'])) : ?>
        <section class="notification is-warning">
            <button class="delete"></button>
            <?php echo implode('<br>', $_SESSION['messages']);
            $_SESSION['messages'] = []; // Clear the user responses?>
        </section>
    <?php endif; ?>

    </header>
    <!-- END PAGE HEADER -->

    <!-- BEGIN MAIN PAGE CONTENT -->
    <main class="container">