<?php

session_start();

include 'db.php';


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
|
| Cart is stored in PHP session for now.
|
*/

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/*
|--------------------------------------------------------------------------
| ADD TO CART
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        isset($_POST['action']) &&
        $_POST['action'] === 'add_to_cart'
    ) {

        $productId = intval($_POST['product_id']);

        if ($productId > 0) {

            if (isset($_SESSION['cart'][$productId])) {

                $_SESSION['cart'][$productId]++;

            } else {

                $_SESSION['cart'][$productId] = 1;

            }
        }


        /*
        Return JSON when request comes from JavaScript
        */

        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {

            header('Content-Type: application/json');

            echo json_encode([
                'success' => true,
                'cart_count' => array_sum($_SESSION['cart'])
            ]);

            exit;
        }
    }
}


/*
|--------------------------------------------------------------------------
| CART COUNT
|--------------------------------------------------------------------------
*/

$cartCount = array_sum($_SESSION['cart']);


/*
|--------------------------------------------------------------------------
| GET PRODUCTS
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        name,
        description,
        price,
        category,
        icon
    FROM vegetables
    ORDER BY id DESC
";

$result = $conn->query($sql);


/*
|--------------------------------------------------------------------------
| PRODUCT VISUAL THEMES
|--------------------------------------------------------------------------
*/

$themes = [
    'ONE PIECE'       => 'theme-purple',
    'NARUTO'          => 'theme-orange',
    'ATTACK ON TITAN' => 'theme-blue',
    'DEMON SLAYER'    => 'theme-red',
    'JUJUTSU KAISEN'  => 'theme-violet',
    'DRAGON BALL'     => 'theme-gold'
];


/*
|--------------------------------------------------------------------------
| DEFAULT THEME
|--------------------------------------------------------------------------
*/

function getTheme($category, $themes)
{
    return $themes[$category] ?? 'theme-purple';
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        ANIMEVERSE | Anime Collectibles
    </title>


    <link
        rel="stylesheet"
        href="css/style.css?v=10"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

</head>


<body>


<!-- =====================================================
     NAVIGATION
     ===================================================== -->

<header class="site-nav">


    <a
        class="brand"
        href="index.php"
    >

        <span class="brand-mark">

            <i class="fa-solid fa-bolt"></i>

        </span>

        <span>
            ANIMEVERSE
        </span>

    </a>


    <nav class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="#universes">
            Universes
        </a>

        <a href="#store">
            Store
        </a>

        <a href="admin.php">
            Admin
        </a>

    </nav>


    <div class="nav-actions">


        <button
            class="icon-button"
            type="button"
            aria-label="Search"
        >

            <i class="fa-solid fa-magnifying-glass"></i>

        </button>


        <button
            class="icon-button cart-trigger"
            type="button"
            aria-label="Open shopping cart"
        >

            <i class="fa-solid fa-bag-shopping"></i>


            <span class="cart-count">

                <?php echo $cartCount; ?>

            </span>

        </button>


        <a
            class="icon-button"
            href="admin.php"
        >

            <i class="fa-regular fa-user"></i>

        </a>

    </div>

</header>



<main>


<!-- =====================================================
     HERO
     ===================================================== -->

<section
    class="hero"
    id="home"
>


    <div class="hero-glow glow-purple"></div>

    <div class="hero-glow glow-blue"></div>


    <div class="hero-copy">


        <div class="eyebrow">

            THE ANIME COLLECTOR'S STORE

        </div>


        <h1>

            Your world.

            <br>

            <span>
                Legendary.
            </span>

        </h1>


        <p>

            Discover collectibles inspired by legendary
            anime worlds. Choose your universe and build
            your collection.

        </p>


        <div class="hero-actions">


            <a
                class="btn btn-primary"
                href="#store"
            >

                Explore Store

                <i class="fa-solid fa-arrow-right"></i>

            </a>


            <a
                class="btn btn-secondary"
                href="#universes"
            >

                Explore Universes

            </a>


        </div>

    </div>



    <div
        class="hero-art"
        aria-hidden="true"
    >

        <div class="orbit orbit-a"></div>

        <div class="orbit orbit-b"></div>


        <div class="hero-core">

            <i class="fa-solid fa-dragon"></i>

        </div>


        <div class="hero-label">

            ANIMEVERSE

        </div>

    </div>


</section>



<!-- =====================================================
     UNIVERSES
     ===================================================== -->

<section
    class="section"
    id="universes"
>


    <div class="section-heading">


        <div class="eyebrow">

            EXPLORE THE WORLDS

        </div>


        <h2>

            Pick your universe.

        </h2>


        <p>

            Every world has a story.
            Every story has something worth collecting.

        </p>


    </div>



    <div class="universe-grid">


        <article class="universe-card purple-card">

            <div class="universe-icon">

                <i class="fa-solid fa-skull-crossbones"></i>

            </div>


            <div class="universe-copy">

                <small>
                    ONE PIECE
                </small>

                <h3>
                    Grand Line
                </h3>

                <p>
                    Set sail beyond the horizon.
                </p>

            </div>

        </article>



        <article class="universe-card orange-card">

            <div class="universe-icon">

                <i class="fa-solid fa-fire-flame-curved"></i>

            </div>


            <div class="universe-copy">

                <small>
                    NARUTO
                </small>

                <h3>
                    Hidden Leaf
                </h3>

                <p>
                    Believe in the way of the ninja.
                </p>

            </div>

        </article>



        <article class="universe-card red-card">

            <div class="universe-icon">

                <i class="fa-solid fa-khanda"></i>

            </div>


            <div class="universe-copy">

                <small>
                    DEMON SLAYER
                </small>

                <h3>
                    Demon Corps
                </h3>

                <p>
                    Breathe. Fight. Protect.
                </p>

            </div>

        </article>



        <article class="universe-card blue-card">

            <div class="universe-icon">

                <i class="fa-solid fa-shield-halved"></i>

            </div>


            <div class="universe-copy">

                <small>
                    ATTACK ON TITAN
                </small>

                <h3>
                    Humanity
                </h3>

                <p>
                    Beyond the walls.
                </p>

            </div>

        </article>



        <article class="universe-card violet-card">

            <div class="universe-icon">

                <i class="fa-solid fa-eye"></i>

            </div>


            <div class="universe-copy">

                <small>
                    JUJUTSU KAISEN
                </small>

                <h3>
                    Cursed Energy
                </h3>

                <p>
                    Enter the world of sorcery.
                </p>

            </div>

        </article>



        <article class="universe-card gold-card">

            <div class="universe-icon">

                <i class="fa-solid fa-dragon"></i>

            </div>


            <div class="universe-copy">

                <small>
                    DRAGON BALL
                </small>

                <h3>
                    Saiyan Legacy
                </h3>

                <p>
                    Power has no limits.
                </p>

            </div>

        </article>


    </div>

</section>



<!-- =====================================================
     DYNAMIC STORE
     ===================================================== -->

<section
    class="section store-section"
    id="store"
>


    <div class="section-heading">


        <div class="eyebrow">

            LIVE FROM THE DATABASE

        </div>


        <h2>

            Made for collectors.

        </h2>


        <p>

            Every product below is loaded directly
            from the Animeverse MySQL database.

        </p>


    </div>



    <div class="product-grid">


<?php

if ($result && $result->num_rows > 0) {


    while ($row = $result->fetch_assoc()) {


        $id =
            (int) $row['id'];


        $name =
            htmlspecialchars(
                $row['name'],
                ENT_QUOTES,
                'UTF-8'
            );


        $description =
            htmlspecialchars(
                $row['description'],
                ENT_QUOTES,
                'UTF-8'
            );


        $category =
            htmlspecialchars(
                $row['category'] ?: 'ANIME',
                ENT_QUOTES,
                'UTF-8'
            );


        $price =
            number_format(
                (float) $row['price'],
                0
            );


        $icon =
            htmlspecialchars(
                $row['icon'] ?: 'fa-star',
                ENT_QUOTES,
                'UTF-8'
            );


        $theme =
            getTheme(
                $row['category'],
                $themes
            );


        $cartQuantity =
            $_SESSION['cart'][$id] ?? 0;

?>


        <article
            class="product-card"
            data-product-id="<?php echo $id; ?>"
        >


            <!-- PRODUCT ICON AREA -->

            <div
                class="product-visual <?php echo $theme; ?>"
            >


                <div class="visual-orb"></div>


                <i
                    class="fa-solid <?php echo $icon; ?> product-icon"
                ></i>


                <span>

                    <?php
                    echo strtoupper(
                        substr(
                            $row['category'],
                            0,
                            3
                        )
                    );
                    ?>

                </span>


            </div>



            <!-- PRODUCT DETAILS -->

            <div class="product-info">


                <div class="product-category">

                    <?php echo $category; ?>

                </div>


                <h3>

                    <?php echo $name; ?>

                </h3>


                <p>

                    <?php echo $description; ?>

                </p>


                <div class="product-bottom">


                    <div>

                        <small>
                            PRICE
                        </small>


                        <strong>

                            ₹<?php echo $price; ?>

                        </strong>

                    </div>



                    <button
                        class="add-cart <?php echo $cartQuantity > 0 ? 'added' : ''; ?>"
                        type="button"
                        data-id="<?php echo $id; ?>"
                    >


                        <i
                            class="fa-solid <?php echo $cartQuantity > 0 ? 'fa-check' : 'fa-plus'; ?>"
                        ></i>


                    </button>


                </div>


            </div>


        </article>


<?php

    }


} else {

?>


        <div class="empty-store">

            <div class="empty-cart-icon">

                <i class="fa-solid fa-box-open"></i>

            </div>


            <h3>
                No collectibles available
            </h3>


            <p>
                Add products from the Admin panel.
            </p>

        </div>


<?php

}

?>


    </div>

</section>



<!-- =====================================================
     FEATURE
     ===================================================== -->

<section
    class="feature-banner"
    id="collections"
>


    <div class="feature-copy">


        <div class="eyebrow">

            THE COLLECTOR'S CODE

        </div>


        <h2>

            Collect the worlds
            that shaped you.

        </h2>


        <p>

            From the first episode to the final battle,
            some stories stay with you forever.

        </p>


        <a
            class="btn btn-primary"
            href="#store"
        >

            Shop Collection

            <i class="fa-solid fa-arrow-right"></i>

        </a>


    </div>



    <div
        class="feature-object"
        aria-hidden="true"
    >

        <i class="fa-solid fa-bolt"></i>

    </div>


</section>


</main>



<!-- =====================================================
     FOOTER
     ===================================================== -->

<footer>


    <div class="footer-brand">

        <i class="fa-solid fa-bolt"></i>

        ANIMEVERSE

    </div>


    <p>

        Built for the generation that grew up
        inside anime worlds.

    </p>


    <div class="footer-links">

        <a href="index.php">
            Home
        </a>

        <a href="#universes">
            Universes
        </a>

        <a href="#store">
            Store
        </a>

        <a href="admin.php">
            Admin
        </a>

    </div>


    <small>

        © 2026 Animeverse.
        All rights reserved.

    </small>


</footer>



<!-- =====================================================
     CART DRAWER
     ===================================================== -->

<div
    class="cart-drawer"
    aria-hidden="true"
>


    <div class="cart-backdrop"></div>


    <aside class="cart-panel">


        <div class="cart-header">


            <div>

                <span>
                    YOUR COLLECTION
                </span>

                <h2>
                    Shopping Bag
                </h2>

            </div>


            <button
                class="cart-close"
                type="button"
            >

                <i class="fa-solid fa-xmark"></i>

            </button>


        </div>



        <div class="cart-items">


<?php

/*
|--------------------------------------------------------------------------
| LOAD CART PRODUCTS
|--------------------------------------------------------------------------
*/

if (!empty($_SESSION['cart'])) {


    $cartIds =
        array_keys(
            $_SESSION['cart']
        );


    $safeIds =
        implode(
            ',',
            array_map(
                'intval',
                $cartIds
            )
        );


    if ($safeIds !== '') {


        $cartSql = "
            SELECT
                id,
                name,
                category,
                price,
                icon
            FROM vegetables
            WHERE id IN ($safeIds)
        ";


        $cartResult =
            $conn->query(
                $cartSql
            );


        $cartTotal = 0;


        if (
            $cartResult &&
            $cartResult->num_rows > 0
        ) {


            while (
                $cartProduct =
                $cartResult->fetch_assoc()
            ) {


                $productId =
                    (int) $cartProduct['id'];


                $quantity =
                    $_SESSION['cart'][$productId];


                $itemPrice =
                    (float) $cartProduct['price'];


                $cartTotal +=
                    $itemPrice *
                    $quantity;


?>


                <div
                    class="cart-item"
                    data-cart-id="<?php echo $productId; ?>"
                >


                    <div
                        class="cart-item-icon <?php echo getTheme($cartProduct['category'], $themes); ?>"
                    >

                        <i
                            class="fa-solid <?php echo htmlspecialchars($cartProduct['icon']); ?>"
                        ></i>

                    </div>



                    <div class="cart-item-info">


                        <small>

                            <?php
                            echo htmlspecialchars(
                                $cartProduct['category']
                            );
                            ?>

                        </small>


                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $cartProduct['name']
                            );
                            ?>

                        </h3>


                        <strong>

                            ₹<?php
                            echo number_format(
                                $itemPrice
                            );
                            ?>

                        </strong>


                        <div class="quantity-controls">


                            <button
                                type="button"
                                class="cart-quantity"
                                data-action="decrease"
                                data-id="<?php echo $productId; ?>"
                            >

                                <i class="fa-solid fa-minus"></i>

                            </button>


                            <b>

                                <?php echo $quantity; ?>

                            </b>


                            <button
                                type="button"
                                class="cart-quantity"
                                data-action="increase"
                                data-id="<?php echo $productId; ?>"
                            >

                                <i class="fa-solid fa-plus"></i>

                            </button>


                        </div>


                    </div>


                    <button
                        type="button"
                        class="remove-item"
                        data-action="remove"
                        data-id="<?php echo $productId; ?>"
                    >

                        <i class="fa-solid fa-trash"></i>

                    </button>


                </div>


<?php

            }

        }

    }


} else {

?>


            <div class="empty-cart">

                <div class="empty-cart-icon">

                    <i class="fa-solid fa-bag-shopping"></i>

                </div>


                <h3>

                    Your collection is empty

                </h3>


                <p>

                    Add something from the store
                    to begin your collection.

                </p>

            </div>


<?php

    $cartTotal = 0;

}

?>


        </div>



        <div class="cart-footer">


            <div class="cart-total-row">

                <span>
                    Total
                </span>


                <strong class="cart-total">

                    ₹<?php
                    echo number_format(
                        $cartTotal ?? 0
                    );
                    ?>

                </strong>

            </div>


            <button
                class="checkout-button"
                type="button"
            >

                Proceed to Checkout

                <i class="fa-solid fa-arrow-right"></i>

            </button>


        </div>


    </aside>

</div>



<!-- =====================================================
     DYNAMIC CART JAVASCRIPT
     ===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        const cartDrawer =
            document.querySelector(
                ".cart-drawer"
            );


        const cartTrigger =
            document.querySelector(
                ".cart-trigger"
            );


        const cartClose =
            document.querySelector(
                ".cart-close"
            );


        const cartBackdrop =
            document.querySelector(
                ".cart-backdrop"
            );


        /*
        |--------------------------------------------------------------------------
        | OPEN CART
        |--------------------------------------------------------------------------
        */

        function openCart() {

            cartDrawer.classList.add(
                "open"
            );

            cartDrawer.setAttribute(
                "aria-hidden",
                "false"
            );

            document.body.classList.add(
                "cart-open"
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE CART
        |--------------------------------------------------------------------------
        */

        function closeCart() {

            cartDrawer.classList.remove(
                "open"
            );

            cartDrawer.setAttribute(
                "aria-hidden",
                "true"
            );

            document.body.classList.remove(
                "cart-open"
            );

        }


        cartTrigger.addEventListener(
            "click",
            openCart
        );


        cartClose.addEventListener(
            "click",
            closeCart
        );


        cartBackdrop.addEventListener(
            "click",
            closeCart
        );


        /*
        |--------------------------------------------------------------------------
        | ADD PRODUCT
        |--------------------------------------------------------------------------
        */

        document
        .querySelectorAll(
            ".add-cart"
        )
        .forEach(
            function (button) {


                button.addEventListener(
                    "click",
                    function () {


                        const productId =
                            this.dataset.id;


                        const formData =
                            new FormData();


                        formData.append(
                            "action",
                            "add_to_cart"
                        );


                        formData.append(
                            "product_id",
                            productId
                        );


                        fetch(
                            "index.php",
                            {
                                method: "POST",
                                headers: {
                                    "X-Requested-With":
                                        "XMLHttpRequest"
                                },
                                body: formData
                            }
                        )
                        .then(
                            response =>
                                response.json()
                        )
                        .then(
                            data => {


                                if (
                                    data.success
                                ) {


                                    const count =
                                        document
                                        .querySelector(
                                            ".cart-count"
                                        );


                                    count.textContent =
                                        data.cart_count;


                                    /*
                                    Change button
                                    */

                                    this.classList.add(
                                        "added"
                                    );


                                    const icon =
                                        this.querySelector(
                                            "i"
                                        );


                                    icon.classList
                                        .remove(
                                            "fa-plus"
                                        );


                                    icon.classList
                                        .add(
                                            "fa-check"
                                        );


                                    /*
                                    Reload only
                                    cart drawer
                                    */

                                    setTimeout(
                                        function () {

                                            window.location.reload();

                                        },
                                        250
                                    );

                                }

                            }
                        )
                        .catch(
                            error => {

                                console.error(
                                    "Cart error:",
                                    error
                                );

                            }
                        );

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CART QUANTITY / REMOVE
        |--------------------------------------------------------------------------
        */

        document
        .querySelectorAll(
            ".cart-quantity, .remove-item"
        )
        .forEach(
            function (button) {


                button.addEventListener(
                    "click",
                    function () {


                        const id =
                            this.dataset.id;


                        const action =
                            this.dataset.action;


                        const formData =
                            new FormData();


                        formData.append(
                            "cart_action",
                            action
                        );


                        formData.append(
                            "product_id",
                            id
                        );


                        fetch(
                            "cart.php",
                            {
                                method: "POST",
                                body: formData
                            }
                        )
                        .then(
                            () => {

                                window.location.reload();

                            }
                        )
                        .catch(
                            error => {

                                console.error(
                                    error
                                );

                            }
                        );

                    }
                );

            }
        );

    }
);

</script>


</body>

</html>