<?php

session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
) {
    exit;
}


$action =
    $_POST['cart_action'] ?? '';


$productId =
    intval(
        $_POST['product_id'] ?? 0
    );


if ($productId <= 0) {
    exit;
}


/*
|--------------------------------------------------------------------------
| INCREASE
|--------------------------------------------------------------------------
*/

if ($action === 'increase') {

    if (
        isset(
            $_SESSION['cart'][$productId]
        )
    ) {

        $_SESSION['cart'][$productId]++;

    }

}


/*
|--------------------------------------------------------------------------
| DECREASE
|--------------------------------------------------------------------------
*/

elseif ($action === 'decrease') {

    if (
        isset(
            $_SESSION['cart'][$productId]
        )
    ) {

        $_SESSION['cart'][$productId]--;


        if (
            $_SESSION['cart'][$productId] <= 0
        ) {

            unset(
                $_SESSION['cart'][$productId]
            );

        }

    }

}


/*
|--------------------------------------------------------------------------
| REMOVE
|--------------------------------------------------------------------------
*/

elseif ($action === 'remove') {

    unset(
        $_SESSION['cart'][$productId]
    );

}


header(
    'Content-Type: application/json'
);


echo json_encode([
    'success' => true,
    'cart_count' =>
        array_sum(
            $_SESSION['cart']
        )
]);

?>
