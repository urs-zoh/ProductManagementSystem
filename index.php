<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Management System</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<h1>Product Management</h1>
<form method="POST" action="">
    <div class="controls">
        <!-- Search -->
        <input type="text" name="search" placeholder="Search Product">
        <select name="searchType">
            <option value="linear">Linear Search</option>
            <option value="binary">Binary Search</option>
        </select>
        <button type="submit" name="searchSubmit">Search</button>

        <hr>

        <!-- Sort -->
        <button class="sort" name="sort" value="quick" type="submit">Quick Sort</button>
        <button class="sort" name="sort" value="bubble" type="submit">Bubble Sort</button>

        <!-- Modify -->
        <button class="modify" name="flip" value="flip" type="submit">Flip List</button>
        <button class="modify" name="changeCase" value="changeCase" type="submit">Change Case</button>
    </div>
</form>
<form id="loginForm" method="POST" action="" style="text-align: center;">
    <label>
        <input type="text" name="username" placeholder="Username" required>
    </label>
    <label>
        <input type="password" name="password" placeholder="Password" required>
    </label>
    <button type="submit" name="login">Login</button>
</form>
<?php
// Product array with prices
$products = [
    "Apple" => [
        ["name" => "iPhone 6s", "price" => 300],
        ["name" => "iPhone 10", "price" => 600],
        ["name" => "iPhone 14", "price" => 1000],
        ["name" => "iPhone 8", "price" => 400]
    ],
    "Samsung" => [
        ["name" => "Galaxy S20", "price" => 800],
        ["name" => "Galaxy S10", "price" => 500],
        ["name" => "Galaxy S9", "price" => 400]
    ],
    "Nokia" => [
        ["name" => "Nokia 3310", "price" => 50],
        ["name" => "Nokia 8110", "price" => 100],
        ["name" => "Nokia 3210", "price" => 30]
    ],
    "Sony" => [
        ["name" => "Xperia 1", "price" => 700],
        ["name" => "Xperia 10", "price" => 400],
        ["name" => "Xperia 5", "price" => 500]
    ]
];

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === 'moderator' && $password === 'password') {
        echo "<div id='productManagement' style='text-align: center;'>
                        <form method='POST' action=''>
                            <input type='text' name='manufacturer' placeholder='Manufacturer' required>
                            <input type='text' name='product' placeholder='Product' required>
                            <input type='number' name='price' placeholder='Price' required>
                            <button type='submit' name='addProduct'>Add Product</button>
                        </form>
                        <form method='POST' action=''>
                            <input type='text' name='manufacturer' placeholder='Manufacturer' required>
                            <input type='text' name='product' placeholder='Product' required>
                            <button type='submit' name='removeProduct'>Remove Product</button>
                        </form>
                    </div>";
    } else {
        echo "<p style='color: red; text-align: center;'>Invalid login!</p>";
    }
}

// Handle adding product
if (isset($_POST['addProduct'])) {
    $manufacturer = $_POST['manufacturer'];
    $product = $_POST['product'];
    $price = $_POST['price'];
    $products[$manufacturer][] = ["name" => $product, "price" => $price];
}

// Handle removing product
if (isset($_POST['removeProduct'])) {
    $manufacturer = $_POST['manufacturer'];
    $product = $_POST['product'];
    foreach ($products[$manufacturer] as $key => $item) {
        if ($item['name'] === $product) {
            unset($products[$manufacturer][$key]);
            break;
        }
    }
}

// Handle sorting
if (isset($_POST['sort'])) {
    $sortType = $_POST['sort'];
    foreach ($products as &$productArray) {
        if ($sortType == 'quick') {
            $productArray = quicksort($productArray);
        } elseif ($sortType == 'bubble') {
            bubbleSort($productArray);
        }
    }
}

// Handle search

if (isset($_POST['searchSubmit']) && isset($_POST['searchType'])) {
    $searchType = $_POST['searchType'];
    $searchValue = $_POST['search'];
    $searchResults = [];

    foreach ($products as $manufacturer => $productArray) {
        if ($searchType == 'binary') {
            $productArray = quicksort($productArray); // Ensure the array is sorted for binary search
            $result = binarySearch($productArray, $searchValue);
            if ($result !== false) {
                $searchResults[$manufacturer][] = $result;
            }
        } elseif ($searchType == 'linear') {
            foreach ($productArray as $product) {
                if (stripos($product['name'], $searchValue) !== false) {
                    $searchResults[$manufacturer][] = $product;
                }
            }
        }
    }

    if (!empty($searchResults)) {
        echo "<h2>Search Results</h2>";
        foreach ($searchResults as $manufacturer => $results) {
            echo "<h3>$manufacturer</h3>";
            echo "<ul>";
            foreach ($results as $result) {
                echo "<li>{$result['name']} - \${$result['price']}</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p>No results found</p>";
    }
}

// Binary Search function
function binarySearch($array, $x) {
    $low = 0;
    $high = count($array) - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if (stripos($array[$mid]['name'], $x) !== false) {
            return $array[$mid];
        }
        if (strcasecmp($array[$mid]['name'], $x) < 0) {
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }
    return false;
}


// Handle flipping
if (isset($_POST['flip'])) {
    foreach ($products as &$productArray) {
        $productArray = array_reverse($productArray);
    }
}

// Handle case changing
if (isset($_POST['changeCase'])) {
    foreach ($products as &$productArray) {
        foreach ($productArray as &$product) {
            $product['name'] = strtoupper($product['name']);
        }
    }
}

// Display products or search results
if (!empty($searchResults)) {
    echo "<h2>Search Results</h2>";
    foreach ($searchResults as $manufacturer => $results) {
        echo "<h3>$manufacturer</h3>";
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>{$result['name']} - {$result['price']}€</li>";
        }
        echo "</ul>";
    }
} else {
    ksort($products);
    foreach ($products as $manufacturer => $productArray) {
        echo "<h2>$manufacturer</h2>";
        echo "<ul>";
        foreach ($productArray as $product) {
            echo "<li>{$product['name']} - {$product['price']}€</li>";
        }
        echo "</ul>";
    }
}

// Quick Sort function
function quicksort($array) {
    if(count($array) < 2) return $array;
    $left = $right = [];
    reset($array);
    $pivot_key = key($array);
    $pivot = array_shift($array);
    foreach($array as $k => $v) {
        if($v['price'] < $pivot['price'])
            $left[$k] = $v;
        else
            $right[$k] = $v;
    }
    return array_merge(quicksort($left), [$pivot_key => $pivot], quicksort($right));
}

// Bubble Sort function
function bubbleSort(&$array) {
    $n = count($array);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($array[$j]['price'] > $array[$j + 1]['price']) {
                $temp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $temp;
            }
        }
    }
}
?>
</body>
</html>
