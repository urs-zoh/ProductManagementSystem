<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Management System</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<h1>Product Management</h1>
<form method="POST" action="">
    <div class="controls">
        <!-- Search -->
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search Product">
            <select name="searchType">
                <option value="binary">Binary Search</option>
                <option value="linear">Linear Search</option>
            </select>
            <button type="submit" name="searchSubmit">Search</button>
        </form>
        
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
// Product array
$products = [
    "Apple" => ["iPhone 6s", "iPhone 10", "iPhone 14", "iPhone 15 Pro Max"],
    "Samsung" => ["Galaxy S10", "Galaxy S20", "Galaxy S6", "Galaxy S21"],
    "Nokia" => ["3310", "N-Gage", "Lumia"],
    "Sony" => ["Xperia", "PlayStation", "Walkman", "Bravia"],
    "LG" => ["G2", "G3", "G4"]
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
    $products[$manufacturer][] = $product;
}

// Handle removing product
if (isset($_POST['removeProduct'])) {
    $manufacturer = $_POST['manufacturer'];
    $product = $_POST['product'];
    if (($key = array_search($product, $products[$manufacturer])) !== false) {
        unset($products[$manufacturer][$key]);
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
            if (binarySearch($productArray, $searchValue)) {
                $searchResults[$manufacturer][] = $searchValue;
            }
        } elseif ($searchType == 'linear') {
            foreach ($productArray as $product) {
                if ($product == $searchValue) {
                    $searchResults[$manufacturer][] = $product;
                    break;
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
                echo "<li>$result</li>";
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
        if ($array[$mid] == $x) {
            return true;
        }
        if ($array[$mid] < $x) {
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
            if (is_string($product)) {
                $product = strtoupper($product);
            }
        }
    }
}

// Display products or search results
if (!empty($searchResults)) {
    echo "<h2>Search Results</h2>";
    foreach ($searchResults as $key => $value) {
        echo "<h3>$key</h3>";
        echo "<ul><li>$value</li></ul>";
    }
} else {
    ksort($products);
    foreach ($products as $key => $value) {
        echo "<h2>$key</h2>";
        echo "<ul>";
        foreach ($value as $product) {
            echo "<li>$product</li>";
        }
        echo "</ul>";
    }
}

// Quick Sort function
function quicksort(&$array) {
    if(count($array) < 2) return $array;
    $left = $right = [];
    reset($array);
    $pivot_key = key($array);
    $pivot = array_shift($array);
    foreach($array as $k => $v) {
        if($v < $pivot)
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
            if ($array[$j] > $array[$j + 1]) {
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
