<?php

include 'db.php';

$message = '';
$messageType = '';


/*
|--------------------------------------------------------------------------
| ADD PRODUCT
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';


    /*
    |--------------------------------------------------------------------------
    | CREATE PRODUCT
    |--------------------------------------------------------------------------
    */

    if ($action === 'add_product') {

        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-star');


        if (
            $name === '' ||
            $category === '' ||
            $price <= 0 ||
            $description === ''
        ) {

            $message =
                'Please fill all required fields correctly.';

            $messageType = 'error';

        } else {

            /*
            ----------------------------------------------------------
            Prepared statement
            ----------------------------------------------------------
            */

            $stmt = $conn->prepare(
                "INSERT INTO vegetables
                (name, price, description, category, icon)
                VALUES (?, ?, ?, ?, ?)"
            );


            if ($stmt) {

                $stmt->bind_param(
                    "sdsss",
                    $name,
                    $price,
                    $description,
                    $category,
                    $icon
                );


                if ($stmt->execute()) {

                    $message =
                        'Product added successfully.';

                    $messageType = 'success';

                } else {

                    $message =
                        'Unable to add product: ' .
                        $stmt->error;

                    $messageType = 'error';

                }


                $stmt->close();

            } else {

                $message =
                    'Database error: ' .
                    $conn->error;

                $messageType = 'error';

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | DELETE PRODUCT
    |--------------------------------------------------------------------------
    */

    if ($action === 'delete_product') {

        $productId =
            intval(
                $_POST['product_id'] ?? 0
            );


        if ($productId > 0) {

            $stmt = $conn->prepare(
                "DELETE FROM vegetables WHERE id = ?"
            );


            if ($stmt) {

                $stmt->bind_param(
                    "i",
                    $productId
                );


                if ($stmt->execute()) {

                    $message =
                        'Product deleted successfully.';

                    $messageType = 'success';

                } else {

                    $message =
                        'Unable to delete product.';

                    $messageType = 'error';

                }


                $stmt->close();

            }

        }

    }

}


/*
|--------------------------------------------------------------------------
| GET PRODUCT COUNT
|--------------------------------------------------------------------------
*/

$countResult =
    $conn->query(
        "SELECT COUNT(*) AS total FROM vegetables"
    );


$productCount = 0;

if ($countResult) {

    $countRow =
        $countResult->fetch_assoc();

    $productCount =
        (int) $countRow['total'];

}


/*
|--------------------------------------------------------------------------
| GET PRODUCTS
|--------------------------------------------------------------------------
*/

$result =
    $conn->query(
        "SELECT
            id,
            name,
            description,
            price,
            category,
            icon
         FROM vegetables
         ORDER BY id DESC"
    );


/*
|--------------------------------------------------------------------------
| CATEGORY ICONS
|--------------------------------------------------------------------------
*/

$categoryIcons = [

    'ONE PIECE' =>
        'fa-skull-crossbones',

    'NARUTO' =>
        'fa-fire-flame-curved',

    'DEMON SLAYER' =>
        'fa-khanda',

    'ATTACK ON TITAN' =>
        'fa-shield-halved',

    'JUJUTSU KAISEN' =>
        'fa-eye',

    'DRAGON BALL' =>
        'fa-dragon',

    'MY HERO ACADEMIA' =>
        'fa-bolt',

    'BLEACH' =>
        'fa-skull',

    'DEATH NOTE' =>
        'fa-book-skull',

    'OTHER' =>
        'fa-star'

];

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
        Animeverse Admin
    </title>


    <link
        rel="stylesheet"
        href="css/style.css?v=11"
    >


    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >


    <style>

        /* =====================================================
           ADMIN PAGE
           ===================================================== */

        .admin-page {
            min-height: 100vh;

            padding:
                70px 5% 100px;
        }


        .admin-container {
            max-width: 1350px;

            margin:
                0 auto;
        }


        /* =====================================================
           ADMIN HEADER
           ===================================================== */

        .admin-heading {
            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            gap: 30px;

            margin-bottom: 45px;
        }


        .admin-heading h1 {
            margin-top: 10px;

            color: white;

            font-size:
                clamp(42px, 6vw, 72px);

            line-height: .95;

            letter-spacing: -4px;
        }


        .admin-heading h1 span {
            background:
                linear-gradient(
                    90deg,
                    #c4b5fd,
                    #8b5cf6,
                    #22d3ee
                );

            -webkit-background-clip: text;

            background-clip: text;

            color: transparent;
        }


        .admin-heading p {
            max-width: 600px;

            margin-top: 18px;

            color:
                #a1a1aa;

            font-size: 14px;

            line-height: 1.7;
        }


        /* =====================================================
           ADMIN STATS
           ===================================================== */

        .admin-stat {
            min-width: 180px;

            padding: 24px;

            border:
                1px solid
                rgba(255,255,255,.1);

            border-radius: 22px;

            background:
                rgba(255,255,255,.04);

            text-align: center;
        }


        .admin-stat i {
            margin-bottom: 10px;

            color:
                #8b5cf6;

            font-size: 22px;
        }


        .admin-stat strong {
            display: block;

            color: white;

            font-size: 32px;

            line-height: 1;
        }


        .admin-stat span {
            display: block;

            margin-top: 7px;

            color:
                #71717a;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: 1.5px;
        }


        /* =====================================================
           MESSAGE
           ===================================================== */

        .admin-message {
            margin-bottom: 25px;

            padding: 15px 18px;

            border-radius: 14px;

            font-size: 13px;
        }


        .admin-message.success {
            color:
                #86efac;

            background:
                rgba(34,197,94,.10);

            border:
                1px solid
                rgba(34,197,94,.18);
        }


        .admin-message.error {
            color:
                #fca5a5;

            background:
                rgba(239,68,68,.10);

            border:
                1px solid
                rgba(239,68,68,.18);
        }


        /* =====================================================
           ADD PRODUCT PANEL
           ===================================================== */

        .admin-panel {
            padding: 32px;

            margin-bottom: 35px;

            border:
                1px solid
                rgba(255,255,255,.09);

            border-radius: 30px;

            background:
                linear-gradient(
                    145deg,
                    rgba(255,255,255,.065),
                    rgba(255,255,255,.025)
                );

            box-shadow:
                0 25px 70px
                rgba(0,0,0,.22);
        }


        .panel-heading {
            margin-bottom: 28px;
        }


        .panel-heading h2 {
            color: white;

            font-size: 24px;

            letter-spacing: -.7px;
        }


        .panel-heading p {
            margin-top: 7px;

            color:
                #71717a;

            font-size: 12px;
        }


        /* =====================================================
           FORM
           ===================================================== */

        .product-form {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;
        }


        .form-group {
            display: flex;

            flex-direction: column;

            gap: 8px;
        }


        .form-group.full {
            grid-column:
                1 / -1;
        }


        .form-group label {
            color:
                #a1a1aa;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: 1px;
        }


        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;

            border:
                1px solid
                rgba(255,255,255,.10);

            outline: none;

            border-radius: 14px;

            color: white;

            background:
                #0b0b10;

            padding:
                14px 15px;

            font-family:
                inherit;

            font-size: 13px;

            transition:
                border-color .25s ease,
                box-shadow .25s ease;
        }


        .form-group textarea {
            min-height: 110px;

            resize: vertical;
        }


        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {

            border-color:
                #8b5cf6;

            box-shadow:
                0 0 0 3px
                rgba(139,92,246,.12);

        }


        .form-group select option {
            color: white;

            background:
                #101014;
        }


        /* =====================================================
           ICON SELECTOR
           ===================================================== */

        .icon-preview {
            display: flex;

            align-items: center;

            gap: 12px;

            margin-top: 5px;

            color:
                #71717a;

            font-size: 11px;
        }


        .icon-preview-box {
            width: 40px;

            height: 40px;

            display: grid;

            place-items: center;

            border-radius: 12px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #7c3aed,
                    #4f46e5
                );
        }


        /* =====================================================
           SUBMIT
           ===================================================== */

        .admin-submit {
            grid-column:
                1 / -1;

            min-height: 52px;

            border: 0;

            border-radius: 50px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 10px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #7c3aed,
                    #4f46e5
                );

            box-shadow:
                0 15px 35px
                rgba(79,70,229,.25);

            cursor: pointer;

            font-family:
                inherit;

            font-size: 13px;

            font-weight: 700;

            transition:
                transform .3s ease,
                box-shadow .3s ease;
        }


        .admin-submit:hover {

            transform:
                translateY(-3px);

            box-shadow:
                0 20px 45px
                rgba(79,70,229,.38);

        }


        /* =====================================================
           PRODUCT TABLE
           ===================================================== */

        .product-list {
            overflow: hidden;

            border:
                1px solid
                rgba(255,255,255,.09);

            border-radius: 30px;

            background:
                #101014;
        }


        .product-list-header {
            padding:
                28px 30px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            border-bottom:
                1px solid
                rgba(255,255,255,.08);
        }


        .product-list-header h2 {
            color: white;

            font-size: 22px;

            letter-spacing: -.5px;
        }


        .product-list-header span {
            color:
                #71717a;

            font-size: 11px;
        }


        .admin-products {
            width: 100%;

            border-collapse:
                collapse;
        }


        .admin-products th {
            padding:
                15px 20px;

            color:
                #71717a;

            background:
                #0c0c0f;

            font-size: 9px;

            font-weight: 800;

            letter-spacing: 1.2px;

            text-align: left;

            white-space: nowrap;
        }


        .admin-products td {
            padding:
                17px 20px;

            color:
                #d4d4d8;

            border-top:
                1px solid
                rgba(255,255,255,.06);

            font-size: 12px;

            vertical-align: middle;
        }


        .admin-product-name {
            display: flex;

            align-items: center;

            gap: 12px;

            min-width: 240px;
        }


        .admin-product-icon {
            width: 45px;

            height: 45px;

            flex-shrink: 0;

            display: grid;

            place-items: center;

            border-radius: 13px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #7c3aed,
                    #4f46e5
                );

            font-size: 16px;
        }


        .admin-product-name strong {
            display: block;

            color: white;

            font-size: 13px;
        }


        .admin-product-name small {
            display: block;

            margin-top: 4px;

            color:
                #71717a;

            font-size: 10px;
        }


        .category-pill {
            display: inline-block;

            padding:
                6px 9px;

            border-radius:
                50px;

            color:
                #c4b5fd;

            background:
                rgba(139,92,246,.10);

            font-size: 9px;

            font-weight: 800;

            letter-spacing: .7px;
        }


        .admin-price {
            color:
                #fff;

            font-size: 14px;

            font-weight: 800;

            white-space: nowrap;
        }


        .delete-button {
            width: 36px;

            height: 36px;

            border: 0;

            border-radius: 50%;

            display: grid;

            place-items: center;

            color:
                #a1a1aa;

            background:
                rgba(255,255,255,.05);

            cursor: pointer;

            transition:
                background .2s ease,
                color .2s ease;
        }


        .delete-button:hover {

            color: white;

            background:
                #dc2626;

        }


        .empty-admin {
            padding:
                70px 30px;

            text-align: center;
        }


        .empty-admin i {
            margin-bottom: 15px;

            color:
                #71717a;

            font-size: 35px;
        }


        .empty-admin h3 {
            color: white;

            font-size: 18px;
        }


        .empty-admin p {
            margin-top: 7px;

            color:
                #71717a;

            font-size: 12px;
        }


        /* =====================================================
           BACK LINK
           ===================================================== */

        .admin-back {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            margin-bottom: 30px;

            color:
                #a1a1aa;

            font-size: 12px;

            transition:
                color .2s ease;
        }


        .admin-back:hover {
            color: white;
        }


        /* =====================================================
           MOBILE
           ===================================================== */

        @media (max-width: 850px) {

            .admin-heading {
                align-items:
                    flex-start;

                flex-direction:
                    column;
            }


            .admin-stat {
                width: 100%;
            }


            .product-form {
                grid-template-columns:
                    1fr;
            }


            .form-group.full {
                grid-column:
                    auto;
            }


            .admin-submit {
                grid-column:
                    auto;
            }


            .product-list {
                overflow-x:
                    auto;
            }


            .admin-products {
                min-width:
                    750px;
            }

        }


        @media (max-width: 500px) {

            .admin-page {
                padding:
                    50px 5% 80px;
            }


            .admin-panel {
                padding:
                    22px;
            }


            .admin-heading h1 {
                font-size:
                    45px;

                letter-spacing:
                    -3px;
            }

        }

    </style>

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
            Store
        </a>

        <a href="index.php#universes">
            Universes
        </a>

        <a href="index.php#store">
            Products
        </a>

        <a href="admin.php">
            Admin
        </a>

    </nav>


    <div class="nav-actions">

        <a
            class="icon-button"
            href="index.php"
        >

            <i class="fa-solid fa-store"></i>

        </a>

    </div>


</header>



<!-- =====================================================
     ADMIN
     ===================================================== -->

<main class="admin-page">


    <div class="admin-container">


        <a
            href="index.php"
            class="admin-back"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Back to Store

        </a>



        <!-- HEADER -->

        <div class="admin-heading">


            <div>


                <div class="eyebrow">

                    ANIMEVERSE CONTROL CENTER

                </div>


                <h1>

                    Manage your
                    <span>universe.</span>

                </h1>


                <p>

                    Add and manage anime collectibles
                    directly in your MySQL database.
                    Products added here automatically
                    appear on the storefront.

                </p>


            </div>



            <div class="admin-stat">


                <i class="fa-solid fa-box-open"></i>


                <strong>

                    <?php echo $productCount; ?>

                </strong>


                <span>
                    PRODUCTS
                </span>


            </div>


        </div>



<?php if ($message !== ''): ?>


        <div
            class="admin-message
            <?php echo $messageType; ?>"
        >

            <i
                class="fa-solid
                <?php
                echo $messageType === 'success'
                    ? 'fa-circle-check'
                    : 'fa-circle-exclamation';
                ?>"
            ></i>

            <?php echo htmlspecialchars($message); ?>

        </div>


<?php endif; ?>



        <!-- =================================================
             ADD PRODUCT
             ================================================= -->

        <section class="admin-panel">


            <div class="panel-heading">


                <h2>

                    Add New Collectible

                </h2>


                <p>

                    Create a product that will appear
                    automatically on the Animeverse store.

                </p>


            </div>



            <form
                class="product-form"
                method="POST"
            >


                <input
                    type="hidden"
                    name="action"
                    value="add_product"
                >



                <!-- NAME -->

                <div class="form-group">


                    <label for="name">

                        PRODUCT NAME

                    </label>


                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Example: Straw Hat Collection"
                        required
                    >


                </div>



                <!-- PRICE -->

                <div class="form-group">


                    <label for="price">

                        PRICE (₹)

                    </label>


                    <input
                        type="number"
                        id="price"
                        name="price"
                        placeholder="1499"
                        min="1"
                        step="0.01"
                        required
                    >


                </div>



                <!-- CATEGORY -->

                <div class="form-group">


                    <label for="category">

                        ANIME UNIVERSE

                    </label>


                    <select
                        id="category"
                        name="category"
                        required
                    >

                        <option value="">
                            Select Anime
                        </option>


                        <?php foreach (
                            $categoryIcons
                            as $categoryName => $categoryIcon
                        ): ?>

                            <option
                                value="<?php
                                echo htmlspecialchars(
                                    $categoryName
                                );
                                ?>"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $categoryName
                                );
                                ?>

                            </option>

                        <?php endforeach; ?>


                    </select>


                </div>



                <!-- ICON -->

                <div class="form-group">


                    <label for="icon">

                        PRODUCT ICON

                    </label>


                    <select
                        id="icon"
                        name="icon"
                        required
                    >

                        <option
                            value="fa-star"
                        >
                            ★ Star
                        </option>

                        <option
                            value="fa-skull-crossbones"
                        >
                            ☠ Skull
                        </option>

                        <option
                            value="fa-fire-flame-curved"
                        >
                            🔥 Fire
                        </option>

                        <option
                            value="fa-shield-halved"
                        >
                            🛡 Shield
                        </option>

                        <option
                            value="fa-eye"
                        >
                            👁 Eye
                        </option>

                        <option
                            value="fa-dragon"
                        >
                            🐉 Dragon
                        </option>

                        <option
                            value="fa-bolt"
                        >
                            ⚡ Bolt
                        </option>

                        <option
                            value="fa-skull"
                        >
                            💀 Skull
                        </option>

                        <option
                            value="fa-book-skull"
                        >
                            📖 Death Note
                        </option>

                    </select>


                    <div class="icon-preview">

                        <div class="icon-preview-box">

                            <i
                                id="iconPreview"
                                class="fa-solid fa-star"
                            ></i>

                        </div>


                        Preview

                    </div>


                </div>



                <!-- DESCRIPTION -->

                <div
                    class="form-group full"
                >


                    <label for="description">

                        DESCRIPTION

                    </label>


                    <textarea
                        id="description"
                        name="description"
                        placeholder="Describe this anime collectible..."
                        required
                    ></textarea>


                </div>



                <!-- SUBMIT -->

                <button
                    class="admin-submit"
                    type="submit"
                >

                    <i class="fa-solid fa-plus"></i>

                    Add Collectible

                </button>


            </form>


        </section>



        <!-- =================================================
             PRODUCT LIST
             ================================================= -->

        <section class="product-list">


            <div class="product-list-header">


                <div>

                    <h2>
                        Current Collectibles
                    </h2>

                </div>


                <span>

                    <?php
                    echo $productCount;
                    ?>
                    products in database

                </span>


            </div>



<?php if (
    $result &&
    $result->num_rows > 0
): ?>


            <table class="admin-products">


                <thead>

                    <tr>

                        <th>
                            PRODUCT
                        </th>

                        <th>
                            UNIVERSE
                        </th>

                        <th>
                            PRICE
                        </th>

                        <th>
                            ACTION
                        </th>

                    </tr>

                </thead>


                <tbody>


<?php while (
    $row =
    $result->fetch_assoc()
): ?>


                    <tr>


                        <td>


                            <div
                                class="admin-product-name"
                            >


                                <div
                                    class="admin-product-icon"
                                >

                                    <i
                                        class="fa-solid
                                        <?php
                                        echo htmlspecialchars(
                                            $row['icon']
                                        );
                                        ?>"
                                    ></i>

                                </div>


                                <div>

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $row['name']
                                        );
                                        ?>

                                    </strong>


                                    <small>

                                        ID #<?php
                                        echo $row['id'];
                                        ?>

                                    </small>

                                </div>


                            </div>


                        </td>



                        <td>


                            <span
                                class="category-pill"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $row['category']
                                );
                                ?>

                            </span>


                        </td>



                        <td>


                            <span
                                class="admin-price"
                            >

                                ₹<?php
                                echo number_format(
                                    $row['price']
                                );
                                ?>

                            </span>


                        </td>



                        <td>


                            <form
                                method="POST"
                                onsubmit="
                                    return confirm(
                                        'Delete this product?'
                                    );
                                "
                            >


                                <input
                                    type="hidden"
                                    name="action"
                                    value="delete_product"
                                >


                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?php
                                    echo $row['id'];
                                    ?>"
                                >


                                <button
                                    type="submit"
                                    class="delete-button"
                                    title="Delete product"
                                >

                                    <i
                                        class="fa-solid fa-trash"
                                    ></i>

                                </button>


                            </form>


                        </td>


                    </tr>


<?php endwhile; ?>


                </tbody>


            </table>


<?php else: ?>


            <div class="empty-admin">


                <i
                    class="fa-solid fa-box-open"
                ></i>


                <h3>

                    No products yet

                </h3>


                <p>

                    Add your first anime collectible
                    using the form above.

                </p>


            </div>


<?php endif; ?>


        </section>


    </div>


</main>



<script>

/*
|--------------------------------------------------------------------------
| ICON PREVIEW
|--------------------------------------------------------------------------
*/

const iconSelector =
    document.getElementById(
        "icon"
    );


const iconPreview =
    document.getElementById(
        "iconPreview"
    );


if (
    iconSelector &&
    iconPreview
) {

    iconSelector.addEventListener(
        "change",
        function () {

            iconPreview.className =
                "fa-solid " +
                this.value;

        }
    );

}

</script>


</body>

</html>